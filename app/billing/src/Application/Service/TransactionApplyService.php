<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Account;
use App\Entity\Payment;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use App\Repository\BillingCycleRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

final class TransactionApplyService implements TransactionApplyUseCase
{
    private BillingCycleRepository $billingCycles;
    private TransactionRepository $transactions;
    private EntityManagerInterface $em; // TODO: Try to opt out of this dependency
    private AccountRepository $accounts;

    public function __construct(
        BillingCycleRepository $billingCycles,
        TransactionRepository $transactions,
        AccountRepository $accounts,
        EntityManagerInterface $em
    )
    {
        $this->billingCycles = $billingCycles;
        $this->transactions = $transactions;
        $this->accounts = $accounts;
        $this->em = $em;
    }

    public function execute(TransactionApply $command): Transaction
    {
        switch ($command->type()) {
            case Transaction::WITHDRAW:
                return $this->withdraw($command->account(), $command->amount());
            case Transaction::DEPOSIT:
                return $this->deposit($command->account(), $command->amount());
            case Transaction::DISBURSEMENT:
                return $this->disbursement($command->account(), $command->amount());
            default:
                throw new Exception(sprintf('Unknown transaction type: %s', $command->type()));
        }
    }

    private function withdraw(Account $account, string $amount): Transaction
    {
        $billingCycle = $this->billingCycles->findCurrentForAccount($account);

        if (!isset($activeBillingCycle)) {
            $billingCycle = $this->billingCycles->open($account);
        }

        // NOTE: Cast to float just for simplicity
        $account->setBalance(
            sprintf('%f', (float)$account->getBalance() - (float)$amount)
        );

        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setBillingCycle($billingCycle);
        $transaction->setCredit($amount);
        $transaction->setDebit('0');
        $transaction->setType(Transaction::WITHDRAW);
        $transaction->setDescription('Task assignment fee');

        return $this->em->wrapInTransaction(function () use ($account, $transaction) {
            $this->accounts->update($account);
            return $this->transactions->add($transaction);
        });
    }

    private function deposit(Account $account, string $amount): Transaction
    {
        $billingCycle = $this->billingCycles->findCurrentForAccount($account);

        if (!isset($activeBillingCycle)) {
            $billingCycle = $this->billingCycles->open($account);
        }

        // NOTE: Cast to float just for simplicity
        $account->setBalance(
            sprintf('%f', (float)$account->getBalance() + (float)$amount)
        );

        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setBillingCycle($billingCycle);
        $transaction->setDebit($amount);
        $transaction->setCredit('0');
        $transaction->setType(Transaction::DEPOSIT);
        $transaction->setDescription('Task completion deposit');

        return $this->em->wrapInTransaction(function () use ($account, $transaction) {
            $this->accounts->update($account);
            return $this->transactions->add($transaction);
        });
    }

    private function disbursement(Account $account, string $amount): Transaction
    {
        $billingCycle = $this->billingCycles->findCurrentForAccount($account);

        if (!isset($billingCycle)) {
            throw new Exception(
                'Disbursement transaction can not be created because there are no active billing cycle found'
            );
        }

        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setBillingCycle($billingCycle);
        $transaction->setDebit($amount);
        $transaction->setCredit('0');
        $transaction->setType(Transaction::DISBURSEMENT);
        $transaction->setDescription(sprintf('Funds disbursement for billing cycle: %s', $billingCycle->getId()));

        $payment = new Payment();
        $payment->setAmount($transaction->getDebit());
        $payment->setDescription($transaction->getDescription());

        $transaction->setPayment($payment);

        $account->setBalance('0');

        return $this->em->wrapInTransaction(function () use ($account, $billingCycle, $transaction) {
            $this->billingCycles->close($billingCycle);
            $this->accounts->update($account);
            return $this->transactions->add($transaction);
        });
    }
}

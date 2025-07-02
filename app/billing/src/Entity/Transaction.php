<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    const WITHDRAW = 'withdraw';
    const DEPOSIT = 'deposit';
    const DISBURSEMENT = 'disbursement';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $publicId = null;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'account', referencedColumnName: 'id', nullable: false)]
    private ?Account $account = null;

    #[ORM\ManyToOne(targetEntity: BillingCycle::class, cascade: ['persist'], inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'billing_cycle', referencedColumnName: 'id', nullable: false)]
    private ?BillingCycle $billingCycle = null;

    #[ORM\OneToOne(targetEntity: Payment::class, inversedBy: 'transaction', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'payment', referencedColumnName: 'id')]
    private ?Payment $payment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    private ?string $credit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    private ?string $debit = null;

    #[ORM\Column(length: 256)]
    private ?string $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->publicId = Uuid::v7();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getBillingCycle(): ?BillingCycle
    {
        return $this->billingCycle;
    }

    public function setBillingCycle(?BillingCycle $billingCycle): static
    {
        $this->billingCycle = $billingCycle;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function setCredit(string $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getDebit(): ?string
    {
        return $this->debit;
    }

    public function setDebit(string $debit): static
    {
        $this->debit = $debit;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublicId(): ?Uuid
    {
        return $this->publicId;
    }

    public function setPublicId(Uuid $publicId): static
    {
        $this->publicId = $publicId;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}

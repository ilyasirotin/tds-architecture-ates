<?php

namespace App\Controller;

use App\Application\Service\ViewFinanceStatement;
use App\Application\Service\ViewFinanceStatementUseCase;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    private Security $security;
    private ViewFinanceStatementUseCase $service;

    public function __construct(Security $security, ViewFinanceStatementUseCase $service)
    {
        $this->security = $security;
        $this->service = $service;
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        // TODO: Implement dashboard views for the top-management and admins

        /** @var User $user */
        $user = $this->security->getUser();

        $statement = $this->service->execute(
            new ViewFinanceStatement($user)
        );

        return $this->render('billing/view.html.twig', [
            'statement' => $statement
        ]);
    }
}

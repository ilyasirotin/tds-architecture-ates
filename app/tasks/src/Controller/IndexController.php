<?php

namespace App\Controller;

use App\Application\Service\ViewTasks;
use App\Application\Service\ViewTasksUseCase;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    private ViewTasksUseCase $service;
    private Security $security;

    public function __construct(ViewTasksUseCase $service, Security $security)
    {
        $this->service = $service;
        $this->security = $security;
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        // TODO: Refactor roles checking
        if (count(array_intersect(['ROLE_ADMIN', 'ROLE_MANAGER'], $user->getRoles())) > 0) {
            $canShuffle = true;
        }

        return $this->render('task/view.html.twig', [
            'tasks' => $this->service->execute(
                new ViewTasks($user)
            ),
            'canShuffle' => isset($canShuffle) ?? false
        ]);
    }
}

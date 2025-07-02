<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Services\RegisterUser;
use App\Application\Services\RegisterUserUseCase;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    private RegisterUserUseCase $service;
    private Security $security;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        RegisterUserUseCase         $service,
        Security                    $security,
        UserPasswordHasherInterface $userPasswordHasher,
    )
    {
        $this->service = $service;
        $this->security = $security;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->service->execute(new RegisterUser($user));

            return $this->security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}

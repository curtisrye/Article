<?php

namespace App\Administration\Presentation\Http;

use App\Administration\Domain\Generator\ApiKeyGenerator;
use App\Administration\Domain\Repository\UserRepository;
use App\Administration\Domain\Security\LoginAuthenticator;
use App\Administration\Presentation\Form\RegistrationFormType;
use App\Administration\Presentation\Input\User;
use App\Core\Domain\Model\User as UserModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ApiKeyGenerator $apiKeyGenerator,
    ) {}

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = UserModel::create($user->getFirstName(), $user->getLastName(), $user->getUserName(), $this->apiKeyGenerator->generate(), null);
            $newUser->setPassword(
                $userPasswordHasher->hashPassword(
                    $newUser,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->userRepository->save($newUser);
            $newUser = $this->userRepository->getByUserName($newUser->getUserName());

            return $userAuthenticator->authenticateUser(
                $newUser,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

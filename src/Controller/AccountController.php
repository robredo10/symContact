<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $errors = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig',
        [
            'hasError' => $errors != null,
            'username' => $username
        ]
    );
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route ("/register", name="account_register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            
            $user->setHash($encoder->encodePassword($user, $user->getHash()));

            $manager->persist($user);

            $manager->flush();

            return $this->redirectToRoute('account_login');
        }

        return $this->render(
            'account/register.html.twig', 
            [
                    'form' => $form->createView()
            ]
        );
    }
}

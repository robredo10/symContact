<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('admin_user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * Permet la suppression d'un utilisateur
     *
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     * 
     */
    public function delete(User $user, EntityManagerInterface $manager, Request $request)
    {   
        $token = $request->query->get('token');
        if($this->isCsrfTokenValid('delete_user', $token)) {
        $currentUser = $this->getUser();

        if ($currentUser !== $user) {
            $manager->remove($user);
            $manager->flush();
            }    
        }
        return $this->redirectToRoute("admin_user");
    }

    /**
     * Permet de modifier l'interface d'un utilisateur
     *
     * @Route("/admin/user/edit/{id}", name="admin_user_edit")
     * 
     */
    public function edit(User $user, EntityManagerInterface $manager, Request $request) {
        
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute("admin_user");
        }

        return $this->render('admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de se connecter sur le dashboard
     * 
     * @Route("/admin/login", name="admin_user_login")
     *
     */
    public function login(AuthenticationUtils $utils)
    {
        $errors = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin_user/login.html.twig',
        [
            'hasError' => $errors != null,
            'username' => $username
        ]
    );
    }

    /**
     * Permet de se deconnecter du dashboard 
     * 
     * @Route("/admin/logout", name="admin_user_logout")
     *
     */
    public function logout() {

    }
}

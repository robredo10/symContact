<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index(UserRepository $userRepository)
    {

        $users = $userRepository->findBy([], ['email' => 'ASC']);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users/delete/{id}", name="admin_users_delete")
     */
    public function delete(Request $request, User $user, EntityManagerInterface $manager){
        
        $token = $request->query->get('token');

        if ($this->isCsrfTokenValid('delete-user', $token)){
            $currentUser = $this->getUser();

            if ($currentUser !== $user ){
                $manager->remove($user);
                $manager->flush();
            }

        }
             

        return $this->redirectToRoute('admin_users');

    }

    /**
     * @Route("/admin/users/edit/{id}", name="admin_users_edit")
     */
    public function edit(Request $request, User $user, EntityManagerInterface $manager){

        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('admin_users');
        } 

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/login", name="admin_users_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $errors = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/user/login.html.twig',
        [
            'hasError' => $errors != null,
            'username' => $username
        ]
    );
    }

    /**
     * @Route("/admin/logout", name="admin_users_logout")
     */
    public function logout(){
    }


}

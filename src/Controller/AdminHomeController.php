<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index(EntityManagerInterface $manager)
    {   
        $nbUsers = $manager->createQuery("SELECT COUNT(u.id) FROM App\Entity\User u")->getSingleScalarResult();
        $nbContacts = $manager->createQuery("SELECT COUNT(c.id) FROM App\Entity\Contact c")->getSingleScalarResult();
        $activeUsers = $manager->createQuery("SELECT u FROM App\Entity\User u LEFT JOIN u.contacts c GROUP BY u.id ORDER BY COUNT(c.id) DESC")->setMaxResults(3)->getResult();

        return $this->render('admin/home/index.html.twig', [
            'nbUsers' => $nbUsers,
            'nbContacts' => $nbContacts,
            'activeUsers' => $activeUsers
        ]);
    }
}

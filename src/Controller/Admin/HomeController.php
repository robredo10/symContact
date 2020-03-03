<?php

namespace App\Controller\Admin;

use App\Service\DashboardService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index(DashboardService $dashboard)
    {
        
        return $this->render('admin/home/index.html.twig', [
            'nbUsers' => $dashboard->getNbUsers(),
            'nbContacts' => $dashboard->getNbContacts(),
            'activeUsers' => $dashboard->getActiveUsers(),
        ]);
    }
}

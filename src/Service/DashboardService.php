<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class DashboardService{

    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function getNbUsers(){
        return $this->manager
        ->createQuery("SELECT COUNT(u.id) FROM App\Entity\User u")
        ->getSingleScalarResult();
    }

    public function getNbContacts(){
        return $this->manager
        ->createQuery("SELECT COUNT(c.id) FROM App\Entity\Contact c")
        ->getSingleScalarResult();


    }    
    
    public function  getActiveUsers(){
        return $this->manager
        ->createQuery("
            SELECT u
            FROM App\Entity\User u
            LEFT JOIN u.contacts c
            GROUP BY u.id 
            ORDER BY COUNT(c.id) DESC
        ")->setMaxResults(3)->getResult();
    }

}
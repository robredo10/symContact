<?php

namespace App\Repository;

use App\Entity\Titi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Titi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Titi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Titi[]    findAll()
 * @method Titi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TitiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Titi::class);
    }

    // /**
    //  * @return Titi[] Returns an array of Titi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Titi
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

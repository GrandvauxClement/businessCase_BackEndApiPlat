<?php

namespace App\Repository;

use App\Entity\Garages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Garages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Garages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Garages[]    findAll()
 * @method Garages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GaragesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Garages::class);
    }

    // /**
    //  * @return Garages[] Returns an array of Garages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Garages
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

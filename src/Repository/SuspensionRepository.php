<?php

namespace App\Repository;

use App\Entity\Suspension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Suspension|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suspension|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suspension[]    findAll()
 * @method Suspension[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuspensionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suspension::class);
    }


    public function findSuspensionInFunctionOfDate($date)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.beginDate < :val')
            ->setParameter('val', $date)
            ->orderBy('s.beginDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Suspension
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

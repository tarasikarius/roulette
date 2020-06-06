<?php

namespace App\Repository;

use App\Entity\Spin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Spin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spin[]    findAll()
 * @method Spin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spin::class);
    }

    // /**
    //  * @return Spin[] Returns an array of Spin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Spin
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

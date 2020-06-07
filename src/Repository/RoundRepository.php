<?php

namespace App\Repository;

use App\Entity\Round;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Round|null find($id, $lockMode = null, $lockVersion = null)
 * @method Round|null findOneBy(array $criteria, array $orderBy = null)
 * @method Round[]    findAll()
 * @method Round[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Round::class);
    }

    public function countPlayers()
    {
        return $this->createQueryBuilder('r')
            ->select('r.id as roundId')
            ->addSelect('count(distinct b.player) as playersCount')
            ->join('r.spins', 's')
            ->join('s.bids', 'b')
            ->join('b.player', 'u')
            ->groupBy('r.id')
            ->getQuery()
            ->getResult();
    }
}

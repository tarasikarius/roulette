<?php

namespace App\Controller;

use App\Entity\Round;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractFOSRestController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/statistics/players", methods={"GET"})
     * @Rest\View(serializerGroups={"user:statistic"})
     */
    public function getPlayersStatistic()
    {
        return $this->em->getRepository(User::class)->findBy(['role' => 'ROLE_PLAYER']);
    }

    /**
     * @Route("/statistics/rounds", methods={"GET"})
     */
    public function getRoundsStatistic()
    {
        return $this->em->getRepository(Round::class)->countPlayers();
    }
}

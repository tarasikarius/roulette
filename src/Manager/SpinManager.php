<?php

namespace App\Manager;

use App\Entity\Round;
use App\Entity\Spin;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SpinManager
{
    private $security;
    private $validator;
    private $em;
    private $bidManager;

    public function __construct(Security $security, ValidatorInterface $validator, EntityManagerInterface $em, BidManager $bidManager)
    {
        $this->security = $security;
        $this->validator = $validator;
        $this->em = $em;
        $this->bidManager = $bidManager;
    }

    public function makeSpin(array $params)
    {
        $bidsData = $params['bids'] ?? null;
        if (null === $bidsData) {
            throw new BadRequestHttpException('Bids can\'t be empty');
        }

        $round = $this->getOpenedRound();
        $winningCell = $this->generateWinningCell($round);

        $spin = new Spin();
        $spin->setWinningCell($winningCell);
        $spin->setInitiator($this->security->getUser());
        $spin->setRound($round);

        foreach ($bidsData as $bidData) {
            $bid = $this->bidManager->create($bidData, false);

            $spin->addBid($bid);
        }

        $errors = $this->validator->validate($spin);
        if (\count($errors)) {
            throw new ConstraintViolationException($errors);
        }

        $this->em->persist($spin);
        $this->em->flush();

        return $spin;
    }

    private function getOpenedRound(): Round
    {
        $round = $this->em->getRepository(Round::class)->findOneBy(['closedAt' => null], ['id' => 'DESC']);

        if (null !== $round && $round->getSpins()->count() < Spin::JACKPOT_CELL) {
            return $round;
        }

        if (null !== $round) {
            $round->setClosedAt(new \DateTime());
            $this->em->persist($round);
        }

        $newRound = new Round();
        $this->em->persist($newRound);

        return $newRound;
    }

    private function generateWinningCell(Round $round): int
    {
        $cells = range(Spin::LOWEST_CELL, Spin::JACKPOT_CELL);
        foreach ($round->getSpins() as $spin) {
            if ($key = array_search($spin->getWinningCell(), $cells)) {
                unset($cells[$key]);
            }
        }
        $cells = array_values($cells);

        return $cells[array_rand($cells, 1)];

//        $min = Spin::LOWEST_CELL - 1;
//        $max = Spin::JACKPOT_CELL - 1;
//
//        $num = mt_rand($min, $max);
//
//        for ($i = $max; $i > $min; $i--) {
//            if ($num < $i) {
//                return $i;
//            }
//        }
    }
}
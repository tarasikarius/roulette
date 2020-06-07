<?php

namespace App\Manager;

use App\Entity\Bid;
use App\Entity\User;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BidManager
{
    private $validator;
    private $em;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->em = $em;
    }

    public function create($params, $flush = true): Bid
    {
        $uid = $params['user'] ?? null;
        $cell = $params['cell'] ?? null;

        if (null === $uid || null === $cell) {
            throw new BadRequestHttpException('Invalid bid data');
        }

        $user = $this->em->getRepository(User::class)->find($uid);
        if (null === $user) {
            throw new BadRequestHttpException('Invalid bid data');
        }

        $bid = new Bid();
        $bid->setCell($cell);
        $bid->setPlayer($user);

        $errors = $this->validator->validate($bid);
        if (\count($errors)) {
            throw new ConstraintViolationException($errors);
        }

        $this->em->persist($bid);

        if ($flush) {
            $this->em->flush();
        }

        return $bid;
    }
}
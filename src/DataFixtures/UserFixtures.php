<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserFixtures extends Fixture
{
    const USERS = ['admin', 'james', 'lars', 'kirk', 'jason'];

    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::USERS as $username) {
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($username.'@example.com');
            $user->setRole($username === 'admin' ? 'ROLE_ADMIN' : 'ROLE_PLAYER');
            $user->setPassword($this->encoderFactory->getEncoder(User::class)->encodePassword($username, null));

            $manager->persist($user);
        }

        $manager->flush();
    }
}

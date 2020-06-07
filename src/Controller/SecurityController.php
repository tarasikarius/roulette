<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SecurityController
{
    /**
     * @Route("/login", methods={"POST"})
     */
    public function loginAction(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, JWTEncoderInterface $jwtEncoder): array
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $userRepository->findOneBy(['username' => $username]);

        if (null === $user) {
            throw new AuthenticationException();
        }

        if (!$passwordEncoder->isPasswordValid($user, $password)) {
            throw new AuthenticationException();
        }

        return ['token' => $jwtEncoder->encode(['username' => $user->getUsername()])];
    }
}

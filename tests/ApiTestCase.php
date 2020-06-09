<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    public function adminAuthRequest(string $method, string $uri, array $parameters = [], array $files = [], array $server = [], string $content = null, bool $changeHistory = true)
    {
        return $this->authenticatedRequest('admin', $method, $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    public function playerAuthRequest(string $method, string $uri, array $parameters = [], array $files = [], array $server = [], string $content = null, bool $changeHistory = true)
    {
        return $this->authenticatedRequest('kirk', $method, $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    public function authenticatedRequest(string $username, string $method, string $uri, array $parameters = [], array $files = [], array $server = [], string $content = null, bool $changeHistory = true)
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername($username);
        $token = static::$container->get('lexik_jwt_authentication.jwt_manager')->create($user);
        $server = array_merge($server, [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        $client->request($method, $uri, $parameters, $files, $server, $content, $changeHistory);

        return $client;
    }
}
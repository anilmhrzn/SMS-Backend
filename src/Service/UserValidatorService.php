<?php

namespace App\Service;

use App\Entity\User;
use App\Service\Interfaces\UserValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserValidatorService implements UserValidatorInterface
{

    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {}

    public function validateUser(string $email, string $password): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user && $this->passwordHasher->isPasswordValid($user, $password)) {
            return $user;
        }
        return null;
    }
}
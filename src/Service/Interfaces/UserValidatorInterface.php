<?php
namespace App\Service\Interfaces;

use App\Entity\User;

interface UserValidatorInterface
{
    public function validateUser(string $email, string $password): ?User;
}
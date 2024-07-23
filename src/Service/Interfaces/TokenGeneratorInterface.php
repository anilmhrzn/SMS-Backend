<?php
namespace App\Service\Interfaces;

use App\Entity\User;

interface TokenGeneratorInterface
{
    public function loadUserData(User $user): array;
    public function validateToken($request):array;

    public function generateToken(User $user): string;
}
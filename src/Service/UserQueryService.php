<?php

namespace App\Service;

use App\Repository\UserRepositoryInterface;
use App\Service\Interfaces\UserQueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Proxy;
use Symfony\Component\Serializer\SerializerInterface;

readonly class UserQueryService implements UserQueryInterface
{
    public function __construct(private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function findSubjectById($userId): array
    {
        $subject = $this->userRepository->findSubjectByUser($userId);
        $subjectRespose = [
            'id' => $subject->getId(),
            'name' => $subject->getName(),
        ];
//        dd($subjectRespose);
        return $subjectRespose;
        // TODO: Implement findSubjectById() method.
    }
}
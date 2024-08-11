<?php

namespace App\Service\Teacher\GetAllTeachers;

use App\Repository\UserRepositoryInterface;

//use GetAllTeachersInterface;
//user G
readonly class GetAllTeacher implements GetAllTeachersInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function getTeachers($semester, int $page = 1, int $limit = 10): array
{
    if($page==0){
        $page=1;
    }
    if($limit==0){
        $limit=10;
    }
    $paginator = $this->userRepository->findByRoleAndSemester($semester, $page, $limit);
//    dd($paginator);
    $totalItems = count($paginator);
    $totalPages = ceil($totalItems / $limit);
    $results = [];
    foreach ($paginator as $user) {
        $results[] = [
            'id' => $user[0]->getId(),
            'name' => $user[0]->getName(),
            'email' => $user[0]->getEmail(),
            'roles' => $user[0]->getRoles(),
            'semester' => $user['semester'],
            'subject' => $user['subject'],
        ];
    }
//dd($results);
    return [
        'data' => $results,
        'totalItems' => $totalItems,
        'currentPage' => $page,
        'totalPages' => $totalPages,
    ];
}

}
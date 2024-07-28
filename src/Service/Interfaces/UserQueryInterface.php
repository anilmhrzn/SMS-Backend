<?php

namespace App\Service\Interfaces;

interface UserQueryInterface
{
    public function findSubjectById($userId): array;
//    public function findByStudentId($studentId): array;
}

<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findAll(): array;
    public function addStudentToUser(int $id,Student $student):User;
}
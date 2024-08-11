<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findAll(): array;
    public function addStudentToUser(int $id,Student $student):User;
    public function findSubjectByUser(int $id):Subject;
    public function findByRole(): array;


}
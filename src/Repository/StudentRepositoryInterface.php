<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface StudentRepositoryInterface
{

    public function findAll(): array;
    public function findAllByLimitAndPage($limit, $page): Paginator;

    public function findById(int $id): ?Student;

    public function addNewStudent(Student $student): void;

    public function findByUser($userId, $limit = null, $offset = null);
    public function addSubjectTostudent($studentId, $subjectId);
}
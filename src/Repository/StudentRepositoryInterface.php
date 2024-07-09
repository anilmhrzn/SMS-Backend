<?php

namespace App\Repository;

use App\Entity\Student;

interface StudentRepositoryInterface
{

    public function findAll(): array;
    public function findAllByLimitAndPage( $limit = null, $offset = null): array;

    public function findById(int $id): ?Student;

    public function addNewStudent(Student $student): void;

    public function findByUser($userId, $limit = null, $offset = null);
    public function addSubjectTostudent($studentId, $subjectId);
}
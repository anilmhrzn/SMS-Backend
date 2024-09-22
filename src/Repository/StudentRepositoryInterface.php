<?php

namespace App\Repository;

use App\Entity\Semester;
use App\Entity\Student;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface StudentRepositoryInterface
{

    public function findAll(): array;
    public function findAllByLimitAndPage($limit, $page): Paginator;
    public function findBySemesterOrName(?string $name,?Semester $semester,$limit, $page): Paginator;

    public function findById(int $id): ?Student;

    public function addNewStudent(Student $student): void;

    public function findByUser($userId, $limit = null, $offset = null);
    public function addSubjectTostudent($studentId, $subjectId);
    public function findByUserWithFilters(int $userId, ?string $name, ?int $semesterId, int $limit, int $page): Paginator;

    public function findStudentIsAllowedToGiveExam(mixed $studentId, $semesterId);

}
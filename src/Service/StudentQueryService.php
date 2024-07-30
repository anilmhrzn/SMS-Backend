<?php

namespace App\Service;

use App\Repository\SemesterRepositoryInterface;
use App\Repository\StudentRepository;
use App\Service\Interfaces\StudentQueryInterface;

readonly class StudentQueryService implements StudentQueryInterface
{

    public function __construct(private StudentRepository $studentRepository, private readonly SemesterRepositoryInterface $semesterRepository)
    {
    }


    public function getTotalStudentsCount(): int
    {
        return $this->studentRepository->count([]);
    }

    public function getTotalStudentsCountOfUser($userId): int
    {
//        dd('here in the get total students count of user');
        return $this->studentRepository->countStudentsOfUser($userId);
    }

    public function findAllByLimitAndPage($limit, $page): array
    {
        $students = $this->studentRepository->findAllByLimitAndPage($limit, $page);
        $studentsArray = [];
        foreach ($students as $student) {
            $studentsArray[] = [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'email' => $student->getEmail(),
                'number' => $student->getNumber(),
            ];
        }
        $totalStudents = $this->getTotalStudentsCount();
        return [
            'students' => $studentsArray,
            'total' => $totalStudents,
            'page' => $page,
            'limit' => $limit
        ];
//        return $studentsArray;
    }

    public function findByUser($userId, $limit, $page): array
    {
        $students = $this->studentRepository->findByUser($userId, $limit, $page);
        $studentsArray = [];
        foreach ($students as $student) {
            $studentsArray[] = [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'email' => $student->getEmail(),
                'number' => $student->getNumber(),
            ];
        }
        return $studentsArray;
    }

    public function findByStudentId($studentId): array
    {
        $student = $this->studentRepository->findById($studentId);
        return [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'email' => $student->getEmail(),
            'number' => $student->getNumber(),
            'photo' => $student->getPhoto(),
            'gender' => $student->getGender(),
        ];

    }


    public function findBySemesterName(?string $name, ?int $semester, $limit, $page): array
    {
        if ($semester !== null && $semester !== 0) {
            $semesterId = $this->semesterRepository->findById($semester);
            if ($semesterId == null) {
                throw new \Exception('Semester not found');
            }
        } else {
            $semesterId = null;
        }
        $students = $this->studentRepository->findBySemesterOrName($name, $semesterId, $limit, $page);
        $studentsArray = [];
        foreach ($students as $student) {
            $studentsArray[] = [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'email' => $student->getEmail(),
                'number' => $student->getNumber(),
            ];
        }
        $totalStudents = $this->studentRepository->countFindBySemesterOrName($name, $semesterId);
        return [
            'students' => $studentsArray,
            'total' => $totalStudents,
            'page' => $page,
            'limit' => $limit
        ];
    }
}
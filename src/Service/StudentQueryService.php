<?php

namespace App\Service;

use App\Dto\AddNewStudentRequest;
use App\Entity\Student;
use App\Repository\StudentRepositoryInterface;
use App\Service\Interfaces\StudentManagementInterface;
use App\Service\Interfaces\StudentQueryInterface;
use App\Service\Interfaces\UserQueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class StudentQueryService implements StudentQueryInterface
{

    public function __construct(private StudentRepositoryInterface $studentRepository)
    {
    }


    public function getTotalStudentsCount(): int {
        return $this->studentRepository->count([]);
    }
    public function getTotalStudentsCountOfUser($userId): int {
//        dd('here in the get total students count of user');
        return $this->studentRepository->countStudentsOfUser($userId);
    }
    public function findAllByLimitAndPage( $limit, $page): array
    {
        $students = $this->studentRepository->findAllByLimitAndPage( $limit, $page);
        $studentsArray = [];
        foreach ($students as $student) {
            $studentsArray[] = [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'email' => $student->getEmail(),
                'number' => $student->getNumber(),
            ];
        }
        $totalStudents = count($studentsArray);
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

    public function findSubjectById(int $id)
    {
        // TODO: Implement findSubjectById() method.
    }
}
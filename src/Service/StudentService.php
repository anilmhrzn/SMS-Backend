<?php

namespace App\Service;

use App\Dto\AddNewStudentRequest;
use App\Entity\Student;
use App\Repository\StudentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class StudentService
{

    public function __construct(private StudentRepositoryInterface $studentRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function createStudent(AddNewStudentRequest $data): array
    {
        $student = new Student();
        $student->setFromDto($data);
        $this->entityManager->beginTransaction();
        try {
            $this->studentRepository->addNewStudent($student);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        $studentData = [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'email' => $student->getEmail(),
            'photo' => $student->getPhoto(),
            'gender' => $student->getGender(),
            'number' => $student->getNumber(),
        ];
        return $studentData;

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
        return $studentsArray;
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
        $studentsArray = [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'email' => $student->getEmail(),
            'number' => $student->getNumber(),
            'photo' => $student->getPhoto(),
            'gender' => $student->getGender(),
            ];
        return $studentsArray;

    }
    public function addSubjectToStudent($studentId, $subjectId)
    {
        $student = $this->studentRepository->findById($studentId);
        $this->studentRepository->addSubjectToStudent($student, $subjectId);
    }
}
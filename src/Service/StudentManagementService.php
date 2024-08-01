<?php

namespace App\Service;

use App\Dto\AddNewStudentRequest;
use App\Entity\Student;
use App\Repository\SemesterRepositoryInterface;
use App\Repository\StudentRepositoryInterface;
use App\Repository\UserRepository;
use App\Service\Interfaces\StudentManagementInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class StudentManagementService implements StudentManagementInterface
{
    public function __construct(private StudentRepositoryInterface $studentRepository, private SemesterRepositoryInterface $semesterRepository, private EntityManagerInterface $entityManager,private UserRepository $userRepository)
    {
    }

    public function createStudent(AddNewStudentRequest $data): array
    {
        $semesterId = $this->semesterRepository->findById($data->getSemesterId());
        $student = new Student();
        if($semesterId === null){
            throw new \Exception('Semester not found');
        }
        $student->setFromDto($data, $semesterId);
        $this->entityManager->beginTransaction();
        try {
            $this->studentRepository->addNewStudent($student);
            $users = $this->userRepository->findBy(['semester' => $semesterId]);
            foreach ($users as $user) {
                $student->addUser($user);
                $user->addStudent($student);
                $this->entityManager->persist($user);
            }
            $this->entityManager->persist($student);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        return [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'email' => $student->getEmail(),
            'photo' => $student->getPhoto(),
            'gender' => $student->getGender(),
            'number' => $student->getNumber(),
            'semester'=> $student->getSemester()->getId()
        ];

    }

    public function addSubjectToStudent($studentId, $subjectId): void
    {
        $student = $this->studentRepository->findById($studentId);
        $this->studentRepository->addSubjectToStudent($student, $subjectId);
    }
}
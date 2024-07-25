<?php
namespace App\Service;
use App\Dto\AddNewStudentRequest;
use App\Entity\Student;
use App\Repository\StudentRepositoryInterface;
use App\Service\Interfaces\StudentManagementInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class StudentManagementService implements StudentManagementInterface
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
        return [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'email' => $student->getEmail(),
            'photo' => $student->getPhoto(),
            'gender' => $student->getGender(),
            'number' => $student->getNumber(),
        ];

    }
    public function addSubjectToStudent($studentId, $subjectId): void
    {
        $student = $this->studentRepository->findById($studentId);
        $this->studentRepository->addSubjectToStudent($student, $subjectId);
    }
}
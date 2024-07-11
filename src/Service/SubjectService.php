<?php
namespace App\Service;
use App\Dto\AddNewSubjectRequest;
use App\Entity\Subject;
use App\Repository\SubjectRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class SubjectService
{
    public function __construct(private SubjectRepositoryInterface $subjectRepository, private EntityManagerInterface $entityManager)
    {
    }
    public function createSubject(AddNewSubjectRequest $data): Subject
    {
        $subject = new Subject();
        $subject->setFromDto($data);
        $this->entityManager->beginTransaction();
        try {
            $this->subjectRepository->addNewSubject($subject);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        return $subject;
    }
    public function findAll(): array
    {
        return $this->subjectRepository->findAll();
    }
//    public function findById($id): array
}

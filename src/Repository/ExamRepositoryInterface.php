<?php
namespace App\Repository;
use App\Entity\Exam;
use App\Entity\Subject;
use Symfony\Component\Validator\Constraints\Date;

interface ExamRepositoryInterface
{
    public function findById(int $id): ?Exam;
    public function findAll(): array;
    public function addNewExam(Exam $exam): void;
    public function findByIdAndOrNameOrDateOrSub(?int $id, ?string $name,?string $date,?Subject $subject): array;
    public function findStudentIsAllowedToGiveExam($studentId, $examId);

//    public function updateExam(Exam $exam): void;
//    public function deleteExam(Exam $exam): void;

}
<?php
namespace App\Repository;
use App\Entity\Exam;
interface ExamRepositoryInterface
{
    public function findById(int $id): ?Exam;
    public function findAll(): array;
    public function addNewExam(Exam $exam): void;
    public function findByIdAndOrName(?int $id, ?string $name): array;
//    public function updateExam(Exam $exam): void;
//    public function deleteExam(Exam $exam): void;

}
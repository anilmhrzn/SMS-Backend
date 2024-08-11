<?php
namespace App\Repository;
use App\Entity\Exam;
use App\Entity\Semester;
use App\Entity\Subject;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Validator\Constraints\Date;

interface ExamRepositoryInterface
{
    public function findById(int $id): ?Exam;
    public function findAll(): array;
    public function findAllByLimitAndPage($limit,$page):Paginator;
    //TODO: do this
//    public function findAllByLimitAndPage($limit, $page): Paginator;

    public function addNewExam(Exam $exam): void;
    public function findByIdAndOrNameOrDateOrSub(?int $id, ?string $name,?string $date,?Semester $semester,$limit, $page): Paginator;
    public function findStudentIsAllowedToGiveExam($studentId, $examId);

//    public function updateExam(Exam $exam): void;
//    public function deleteExam(Exam $exam): void;

}
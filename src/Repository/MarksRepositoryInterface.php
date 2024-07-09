<?php
namespace App\Repository;
use App\Entity\Marks;

interface MarksRepositoryInterface
{
    public function addMark(Marks $mark): void;
    public function viewMarks(array $data): array;
//    public function addMarks(array $marks): void;
//    public function getMarks(int $studentId, int $examId): array;
//    public function updateMarks(array $marks): void;
//    public function deleteMarks(int $studentId, int $examId): void;
//public  function getMarks();
}
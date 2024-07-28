<?php
namespace App\Service\Interfaces;

interface StudentQueryInterface {
    public function getTotalStudentsCount(): int;
    public function getTotalStudentsCountOfUser($userId): int;
    public function findAllByLimitAndPage($limit, $page): array;
    public function findByUser($userId, $limit, $page): array;
    public function findByStudentId($studentId): array;
//    public function findByStudentId($studentId): array;
}

<?php
namespace App\Service\Interfaces;

use App\Entity\Semester;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface StudentQueryInterface {

    public function getTotalStudentsCount(): int;

    public function getTotalStudentsCountOfUser($userId): int;

    public function findAllByLimitAndPage($limit, $page): array;
    public function findBySemesterName(?string $name,?int $semester,$limit, $page): array;
    public function findByUser($userId, $limit, $page): array;

}

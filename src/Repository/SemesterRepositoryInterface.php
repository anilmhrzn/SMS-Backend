<?php
namespace App\Repository;
use App\Entity\Semester;

interface SemesterRepositoryInterface
{
    public function findById(int $semester): ?Semester;
    public function findAll(): array;

}

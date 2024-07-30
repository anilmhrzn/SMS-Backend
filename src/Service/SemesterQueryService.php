<?php

namespace App\Service;

use App\Repository\SemesterRepositoryInterface;
use App\Service\Interfaces\SemesterQueryInterface;

class SemesterQueryService implements SemesterQueryInterface
{
    public function __construct(private SemesterRepositoryInterface $semesterRepository)
    {
    }
    public function getAllSemester(): array
    {
        $semesters = $this->semesterRepository->findAll();
        $data = [];

        foreach ($semesters as $semester) {
            $data[] = [
                'id' => $semester->getId(),
                'semester' => $semester->getSemester(),


            ];
        }
        return $data;
    }
}
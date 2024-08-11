<?php

namespace App\Service\Interfaces;

interface ExamQueryInterface
{
    public function findAllByLimitAndPage($limit, $page): array;
    public function searchExam(?int $id, ?string $name, ?string $date,?int $semester,$limit, $page): array;


}

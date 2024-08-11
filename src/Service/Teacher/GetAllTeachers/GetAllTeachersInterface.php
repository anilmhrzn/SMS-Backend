<?php
namespace App\Service\Teacher\GetAllTeachers;
interface GetAllTeachersInterface
{
   public function getTeachers($semester,int $page,int $limit): array;
}
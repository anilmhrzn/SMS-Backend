<?php
namespace App\Service\Interfaces;

use App\Dto\AddNewStudentRequest;

interface StudentManagementInterface {
    public function createStudent(AddNewStudentRequest $data): array;
    public function addSubjectToStudent($studentId, $subjectId);
}
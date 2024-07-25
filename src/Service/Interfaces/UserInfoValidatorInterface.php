<?php
namespace App\Service\Interfaces;

use App\Dto\AddNewStudentRequest;

interface UserInfoValidatorInterface {
    public function validate(AddNewStudentRequest $dto): array;
}
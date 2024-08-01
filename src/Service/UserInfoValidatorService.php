<?php

namespace App\Service;

use App\Service\Interfaces\UserInfoValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Dto\AddNewStudentRequest;

class UserInfoValidatorService implements UserInfoValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(AddNewStudentRequest $dto): array
    {
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages = $error->getMessage();
            }
            return [$errorMessages];
        }
        return [];
    }

}
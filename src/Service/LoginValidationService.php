<?php

namespace App\Service;

use App\Dto\LoginRequestDTO;
use App\Service\Interfaces\UserValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class LoginValidationService
{

    public function __construct(private ValidatorInterface $validator, private UserValidatorInterface $userValidator)
    {
    }

    public function validateLoginDTO(LoginRequestDTO $loginDTO): array
    {
        $errors = $this->validator->validate($loginDTO);
        $errorMessages = [];

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $message = str_replace('{{ value }}', $error->getInvalidValue(), $error->getMessageTemplate());
                $errorMessages[] = $message;
            }
            return ['errors' => $errorMessages];
        }

        $user = $this->userValidator->validateUser($loginDTO->getEmail(), $loginDTO->getPassword());
        if (!$user) {
            $errorMessages[] = 'Invalid email or password';
            return ['errors' => $errorMessages];
        }

        return ['user' => $user];
    }
}
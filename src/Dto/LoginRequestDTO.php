<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class LoginRequestDTO
{


    #[Assert\NotBlank(message: "Email should not be blank.")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    private string $email;

    #[Assert\NotBlank(message: "Password should not be blank.")]
    private string $password;

    public function __construct(array $requestData)
    {
        $this->email = $requestData['email'] ?? null;
        $this->password = $requestData['password'] ?? null;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

}
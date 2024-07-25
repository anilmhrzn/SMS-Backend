<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class AddNewStudentRequest
{
    #[Assert\NotBlank(message: 'Name should not be blank')]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Name should be at least 2 characters long', maxMessage: 'Name should not be longer than 50 characters')]
    private ?string $name;

    #[Assert\NotBlank(message: 'Email should not be blank')]
    #[Assert\Email(message: 'Email should be a valid email address')]
    private ?string $email;

    #[Assert\NotBlank(message: 'Photo should not be blank')]
    private ?string $photo;

    #[Assert\NotBlank(message: 'Gender should not be blank')]
    #[Assert\Choice(
        choices: ['male', 'female', 'others'],
        message: 'Gender must be either "male", "female", or "others".'
    )]
    private ?string $gender;

    #[Assert\NotBlank(message: 'Number should not be blank')]
    #[Assert\NotNull(message: 'Null given to the number.You must specify at least one number')]
    #[Assert\Count(
        min: 1,
        max: 4,
        minMessage: "You must specify at least one number.",
        maxMessage: "You cant add more than 4 numbers"
    )]
    private ?array $number;

    function __construct(array $requestData)
    {

        $this->name = $requestData['name'] ?? null;
        $this->email = $requestData['email'] ?? null;
        $this->photo = $requestData['photo'] ?? null;
        $this->number = $requestData['number'] ?? null;
        $this->gender = $requestData['gender'] ?? null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getNumber(): ?array
    {
        return $this->number;
    }
}
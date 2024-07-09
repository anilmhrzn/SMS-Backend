<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class RegistrationRequest
{

    #[Assert\NotBlank(message: 'name should not be blank')]
    private string $name;
    #[Assert\NotBlank(message: 'email should not be blank')]
    #[Assert\NotBlank(message: 'Email should not be blank')]
    #[Assert\Email(message: 'Email should be a valid email address')]
    private string $email;
    #[Assert\NotBlank(message: 'password should not be blank')]
    private string $password;
    #[Assert\NotBlank(message: 'Subject should not be blank')]

    private string $subject;



    function __construct($name, $email, $password,$subject)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->subject = $subject;


    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }


}
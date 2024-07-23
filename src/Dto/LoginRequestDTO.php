<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequestDTO
{


    #[Assert\NotBlank(message: "Email should not be blank.")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    public string $email;

    /**
     * @Assert\NotBlank(message="Password should not be blank.")
     */
    public string $password;

    public function __construct(array $requestData)
    {
        $this->email = $requestData['email'] ?? '';
        $this->password = $requestData['password'] ?? '';
    }
}
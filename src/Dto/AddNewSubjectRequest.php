<?php
namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class AddNewSubjectRequest
{
    public function __construct(
        #[Assert\NotBlank(message: "Name is required")]
        private string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
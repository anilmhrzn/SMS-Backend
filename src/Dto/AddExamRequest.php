<?php

namespace App\Dto;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

readonly class AddExamRequest
{
    #[Assert\NotBlank(message: 'Date cannot be blank')]
    private DateTime $date;

    public function __construct(
        DateTime       $date,
        #[Assert\NotBlank(message: 'Name cannot be blank')]
        private string $name,

        #[Assert\NotBlank(message: 'Subject should not be blank')]
        private int    $subject
    )
    {
        $this->date = $date;

    }
    public function getDate(): DateTime
    {
        return new $this->date;

    }

    public function getSubject(): int
    {
        return $this->subject;
    }
    public function getName(): string
    {
        return $this->name;
    }
}
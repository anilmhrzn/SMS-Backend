<?php
namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class AddNewMark
{
    public function __construct(
        #[Assert\NotBlank(message: 'Student cannot be blank')]
        private int $student,

        #[Assert\NotBlank(message: 'Exam cannot be blank')]
        private int $exam,

        #[Assert\NotBlank(message: 'Mark cannot be blank')]
        private int $mark
    ) {
    }

    public function getStudent(): int
    {
        return $this->student;
    }

    public function getExam(): int
    {
        return $this->exam;
    }

    public function getMark(): int
    {
        return $this->mark;
    }

}

<?php

namespace App\Dto;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Component\Validator\Constraints\DateTime;

readonly class AddAttendanceRequest
{
    public function __construct(
        #[Assert\NotBlank(message: "Student id should not be blank")]
        #[Assert\Type(type: "integer", message: "Student id should be an integer")]
        #[Assert\Positive(message: "Student id should be a positive integer")]
        public int  $studnetId,

        #[Assert\NotBlank(message: "Date should not be blank")]
        public DateTime $date,

        #[Assert\NotBlank(message: "Status should not be blank")]
        #[Assert\Type(type: "bool", message: "Status should be a boolean")]
        #[Assert\NotNull(message: "Status should not be null")]
        public bool $status,

        #[Assert\NotBlank(message: "User id should not be blank")]
        #[Assert\Type(type: "integer", message: "User id should be an integer")]
        #[Assert\Positive(message: "User id should be a positive integer")]
        public int  $userId
    )
    {
    }

    public function getStudentId(): int
    {
        return $this->studnetId;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }


}
<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class MarksUploadDTO
{
    #[Assert\NotBlank(message: "No file was uploaded")]
    #[Assert\File(mimeTypes: ["text/csv", "text/plain", "application/vnd.ms-excel"], mimeTypesMessage: "Invalid file type. Only CSV files are allowed.")]
    public ?UploadedFile $csv_file = null;

    #[Assert\NotBlank(message: "Exam ID is required")]
    public ?int $exam_id = null;

    #[Assert\NotBlank(message: "Subject ID is required")]
    public ?int $subject_id = null;





    public function __construct(array $requestData, UploadedFile $csv_file)
    {
        $this->exam_id = $requestData['exam_id'] ?? null;
        $this->subject_id = $requestData['subject_id'] ?? null;
        $this->csv_file = $csv_file;
    }
}
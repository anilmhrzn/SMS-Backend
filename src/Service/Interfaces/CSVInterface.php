<?php
namespace App\Service\Interfaces;

use App\Dto\MarksUploadDTO;

interface CSVInterface {
//    public function processCsv($file): array;
    public function validateAndParseCSV(MarksUploadDTO $dto, array $requiredHeaders): iterable ;

}
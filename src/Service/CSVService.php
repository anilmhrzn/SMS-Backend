<?php

namespace App\Service;

use App\Dto\MarksUploadDTO;
use App\Repository\ExamRepositoryInterface;
use App\Repository\StudentRepositoryInterface;
use App\Service\Interfaces\CSVInterface;
use League\Csv\Reader;
use League\Csv\Exception as CsvException;

class CSVService implements CSVInterface
{
public function __construct(private readonly StudentRepositoryInterface $studentRepository, private readonly ExamRepositoryInterface $examRepository)
{
}



    public function validateAndParseCSV(MarksUploadDTO $dto, array $requiredHeaders): iterable
    {
        if (!in_array($dto->csv_file->getMimeType(), ['text/csv', 'text/plain', 'application/vnd.ms-excel'])) {
            throw new CsvException('Invalid file type. Only CSV files are allowed.');
        }

        $csv = Reader::createFromPath($dto->csv_file->getRealPath(), 'r');
        $csv->setHeaderOffset(0);
        $headers = $csv->getHeader();

        $extraHeaders = array_diff($headers, $requiredHeaders);
        $missingHeaders = array_diff($requiredHeaders, $headers);
        if (!empty($extraHeaders) || !empty($missingHeaders)) {
            $errorMessages = [];
            if (!empty($missingHeaders)) {
                $errorMessages[] = 'Missing headers: ' . implode(', ', $missingHeaders);
            }
            if (!empty($extraHeaders)) {
                $errorMessages[] = 'Extra headers: ' . implode(', ', $extraHeaders);
            }
            throw new CsvException('Incorrect CSV format. ' . implode(' ', $errorMessages));
        }
        $records = $csv->getRecords();
        $ignoredRecords=[];
        foreach ($records as $index => $record) {
            $studentId = $record['StudentID'];
            $marks = $record['Marks'];
            $student = $this->studentRepository->find($studentId);
            if (!$student) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Student ID $studentId not found"];
                continue;
            }

            $exam = $this->examRepository->find($dto->exam_id);


            $studentInExam = $this->examRepository->findStudentIsAllowedToGiveExam($studentId, $exam->getId());

            if (!$studentInExam) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Student ID $studentId not eligibe to give exam"];
                continue;
            }


            if ($marks < 0 || $marks > 100) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Marks $marks are invalid (must be between 0 and 100)"];

            }
        }
        return $ignoredRecords;
    }
}
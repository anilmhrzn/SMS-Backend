<?php

namespace App\Service;

use App\Dto\MarksUploadDTO;
use App\Entity\Marks;
use App\Repository\ExamRepositoryInterface;
use App\Repository\MarksRepository;
use App\Repository\StudentRepositoryInterface;
use App\Repository\SubjectRepository;
use App\Service\Interfaces\CSVInterface;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Exception as CsvException;

class CSVService implements CSVInterface
{
public function __construct(private readonly MarksRepository $marksRepository,private SubjectRepository $subjectRepository,private EntityManagerInterface $entityManager,private readonly StudentRepositoryInterface $studentRepository, private readonly ExamRepositoryInterface $examRepository)
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
//dd($exam);
            if (!$exam) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Exam ID $dto->exam_id not found"];
                continue;
            }
            $subject = $this->subjectRepository->find($dto->subject_id);
//dd($exam);
            if (!$subject) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Exam ID $dto->exam_id not found"];
                continue;
            }
//            dd($exam->getSemester()->getId());
            $studentInExam=$this->studentRepository->findStudentIsAllowedToGiveExam($studentId,$exam->getSemester()->getId());

//            $studentInExam = $this->examRepository->findStudentIsAllowedToGiveExam($studentId, $exam->getId());
//dd($studentInExam);
            if (!$studentInExam) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Student ID $studentId not eligibe to give exam"];
                continue;
            }


            if ($marks < 0 || $marks > 100) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Marks $marks are invalid (must be between 0 and 100)"];
                continue;

            }
            $marks = $this->marksRepository->findBySubjectAndExam($subject, $exam,$student);
            if($marks){
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Marks already added for this student"];
            }else{
                $marks=new Marks();
                $marks->setStudent($student);
                $marks->setExam($exam);
                $marks->setSubject($subject);
            }
            $marks->setMarkObtained($record['Marks']);

              $this->entityManager->persist($marks);
//            dump('hello',$studentId);
        }
        $this->entityManager->flush();
        return $ignoredRecords;
    }

}
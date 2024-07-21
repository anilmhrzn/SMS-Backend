<?php

namespace App\Controller\marks;

use App\Entity\Exam;
use App\Entity\Marks;
use App\Entity\Student;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class AddMarksOfAExamController extends AbstractController
{
    #[Route('/api/add/marks/of-exam', name: 'app_add_marks_of_a_exam', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $file = $request->files->get('csv_file');
        $data = $request->request->all();
        $ignoredRecords = [];
        if (!$file) {
            return $this->json(['error' => 'No file was uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $validMimeTypes = ['text/csv', 'text/plain', 'application/vnd.ms-excel'];
        if (!in_array($file->getMimeType(), $validMimeTypes)) {
            return $this->json(['error' => 'Invalid file type. Only CSV files are allowed.'], Response::HTTP_BAD_REQUEST);
        }

        // After setting the header offset for the CSV
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setHeaderOffset(0);
        $headers = $csv->getHeader(); // Get the headers

        $requiredHeaders = ['StudentID', 'Marks'];

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
            return $this->json([
                'error' => 'Incorrect CSV format. ' . implode(' ', $errorMessages)
            ], Response::HTTP_BAD_REQUEST);
        }

        $records = $csv->getRecords();
        foreach ($records as $index => $record) {
            $studentId = $record['StudentID'];
            $marks = $record['Marks'];
            $student = $entityManager->getRepository(Student::class)->find($studentId);
            $exam = $entityManager->getRepository(Exam::class)->find($data['exam_id']);
            $studentInExam = $entityManager->getRepository(Exam::class)->findStudentIsAllowedToGiveExam($studentId,$data['exam_id']);


            if (!$student) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Student ID $studentId not found"];
                continue;
            }
            if (!$studentInExam) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Student ID $studentId not eligibe to give exam"];
                continue;
            }

            // Check if marks are within the valid range
            if ($marks < 0 || $marks > 100) {
                $ignoredRecords[] = ['row' => $index + 1, 'reason' => "Marks $marks are invalid (must be between 0 and 100)"];
                continue;
            }

            $existingMark = $entityManager->getRepository(Marks::class)->findOneBy([
                'student' => $student,
                'exam' => $exam,
            ]);

            if ($existingMark) {
                $existingMark->setMarkObtained($marks);
            } else {
                $mark = new Marks();
                $mark->setStudent($student);
                $mark->setExam($exam);
                $mark->setMarkObtained($marks);
                $entityManager->persist($mark);
            }
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Marks added successfully',
            'error' => $ignoredRecords
        ]);
    }
}
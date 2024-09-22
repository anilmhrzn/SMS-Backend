<?php

namespace App\Controller\marks;

use App\Repository\ExamRepository;
use App\Repository\MarksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FailedStudentsOfLatestExamController extends AbstractController
{
    public function __construct(private ExamRepository $examRepository, private MarksRepository $marksRepository)
    {

    }

    #[Route('/failed/students/of/latest/exam', name: 'app_failed_students_of_latest_exam')]
    public function index(): Response
    {
        $latestExam = $this->examRepository->findLatestTakenExam();
        if ($latestExam) {
            $latestExamId = $latestExam->getId();
            $failedStudents = $this->marksRepository->findFailedStudentsByExamId($latestExamId);
            return $this->json($failedStudents);
        } else {
            return $this->json(['message' => 'No exam found'], Response::HTTP_NOT_FOUND);
        }

    }

    #[Route('/api/exam/latest/no-of-failed-students', name: 'app_failed_students_count_of_latest_exam')]
    public function countFailedStudentsOfLatestExam(Request $request): Response
    {
        $semester = $request->query->get('semester');
        $latestExam = $this->examRepository->findLatestTakenExam($semester);

        if ($latestExam) {
            $latestExamId = $latestExam->getId();
//            dd($latestExamId);

            $failedStudentsCount = $this->marksRepository->countFailedStudentsByExamId($latestExamId);
            return $this->json(['failed_students_count' => $failedStudentsCount]);
        } else {
            return $this->json(['message' => 'No exam found'], Response::HTTP_NOT_FOUND);
        }
    }
}

<?php

namespace App\Controller\exam;

use App\Repository\ExamRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LatestTakenExamController extends AbstractController
{
    public function __construct(private ExamRepository $examRepository)
    {
    }

    // src/Controller/LatestTakenExamController.php

    #[Route('/latest/taken/exam', name: 'app_latest_taken_exam', methods: ['GET'])]
    public function index(): Response
    {
        $latestExam = $this->examRepository->findLatestTakenExam();

        if ($latestExam) {
            $latestExamInfo = [
                'id' => $latestExam->getId(),
                'name' => $latestExam->getName(),
                'date' => $latestExam->getDate(),
                'subject' => $latestExam->getSubject()->getName()
            ];
            return $this->json($latestExamInfo);
        }
        else{
            return $this->json(['message' => 'No exam found'], Response::HTTP_NOT_FOUND);
        }

    }

}

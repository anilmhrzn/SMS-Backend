<?php

namespace App\Controller\exam;

use App\Repository\ExamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetComingExamsController extends AbstractController
{
    #[Route('/get/coming/exams', name: 'app_get_coming_exams')]
    public function index(ExamRepository $examRepository): JsonResponse
    {
        $today = new \DateTime();
        $futureDate = (new \DateTime())->modify('+1 month'); // Adjust based on your needs

        $comingExams = $examRepository->findComingExams($today, $futureDate);
        return new JsonResponse([
            'comingExams' => array_map(function ($exam) {
                return [
                    'id' => $exam->getId(),
                    'name' => $exam->getName(),
                    'date' => $exam->getDate()->format('Y-m-d'),
                    'subject' => $exam->getSubject()->getName(), // Ensure your Subject entity has a getName() method
                ];
            }, $comingExams)
        ]);
    }

    // In GetComingExamsController.php

    #[Route('/api/exams/getNoOfComingExams', name: 'app_get_coming_exams_count')]
    public function countComingExams(ExamRepository $examRepository): JsonResponse
    {
        $today = new \DateTime();
        $futureDate = (new \DateTime())->modify('+1 month'); // Adjust based on your needs

        $countComingExams = $examRepository->countComingExams($today, $futureDate);

        return new JsonResponse([
            'noOfComingExams' => $countComingExams
        ]);
    }

}

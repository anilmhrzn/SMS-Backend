<?php
namespace App\Controller\exam;
use App\Service\Interfaces\ExamQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchExamController extends AbstractController
{
    public function __construct(private readonly ExamQueryInterface $examQueryService)
    {
    }

    #[Route('/api/search_exam', name: 'search_exam', methods: ['GET'])]
    public function searchExam(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        $name = $request->query->get('name');
        $date = $request->query->get('date');
        $page = (int)$request->query->get('page', 1);
        $limit = (int)$request->query->get('limit', 10);
        $subject = (int)$request->query->get('subject');
        $exams = $this->examQueryService->searchExam($id, $name, $date, $subject,$limit,$page);
        return new JsonResponse($exams, Response::HTTP_OK, ['content-type' => 'application/json']);
    }
}
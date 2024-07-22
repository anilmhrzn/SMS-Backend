<?php

namespace App\Controller\marks;

use App\Repository\MarksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ViewMarksByExamIdController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    #[Route('api/exams/view/marks', name: 'app_view_marks_by_exam_id', methods: ['POST'])]
    public function index(Request $request, MarksRepository $marksRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $examId = $data['exam_id'] ?? null;

        if (!is_numeric($examId)) {
            return new JsonResponse(['error' => 'Invalid exam ID'], Response::HTTP_BAD_REQUEST);
        }

        $marks = $marksRepository->findMarksByExamId((int)$examId);
//        dd($marks);
        $json = $this->serializer->serialize($marks, 'json', [
            AbstractNormalizer::ATTRIBUTES => [ 'student' => ['id' => 'student_Id', 'name' => 'student_name'],'mark_obtained'],
        ]);

        return new JsonResponse($json, Response::HTTP_OK, ['content-type' => 'application/json'], true);
    }
}

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

    $marks = $marksRepository->findMarksByExamId((int) $examId);

    // Since the data is already in a suitable format, directly encode it to JSON
    // This bypasses the need for the serializer's AbstractNormalizer::ATTRIBUTES
    $jsonMarks = json_encode($marks);

    return new JsonResponse($jsonMarks, Response::HTTP_OK, ['content-type' => 'application/json'], true);
}
}

<?php

namespace App\Controller\exam;

use App\Entity\Exam;
use App\Service\Interfaces\ExamQueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetAllExamsController extends AbstractController
{
    public function __construct(private readonly ExamQueryInterface $examQueryService)
    {
    }

    #[Route('/api/exams', name: 'get_exams', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $page = (int)$request->query->get('page', 1);
        $limit = (int)$request->query->get('limit', 10);
        $exams = $this->examQueryService->findAllByLimitAndPage($limit, $page);
        return new JsonResponse($exams, Response::HTTP_OK, ['content-type' => 'application/json']);
    }
}
<?php

declare(strict_types=1);

namespace App\Controller\subject;

use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubjectsBySemesterController extends AbstractController
{
    public function __construct(private readonly SubjectRepository $subjectRepository)
    {
    }
    #[Route('/api/subjects-by-semester')]
    public function index(Request $request): JsonResponse
    {
        $semesterId = (int)$request->query->get('semester_id');
        $subjects = $this->subjectRepository->findBySemester($semesterId);
        return new JsonResponse($subjects, Response::HTTP_OK, ['content-type' => 'application/json']);
    }
}

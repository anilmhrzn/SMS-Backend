<?php

namespace App\Controller\students;

use App\Service\Interfaces\StudentQueryInterface;
use App\Service\Interfaces\UserQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewAllStudentsController extends AbstractController
{
    public function __construct(private readonly StudentQueryInterface $studentQueryService)
    {
    }

    #[Route('api/students', name: 'app_student_index_all', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = (int)$request->query->get('page', 1);
        $limit = (int)$request->query->get('limit', 10);
        $semester = (int)$request->query->get('semester');
        $name = $request->query->get('name');
        $students = $this->studentQueryService->findBySemesterName($name,$semester,$limit, $page);

        return new JsonResponse($students, Response::HTTP_OK, ['content-type' => 'application/json']);
    }
}

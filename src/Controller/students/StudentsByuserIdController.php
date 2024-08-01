<?php

declare(strict_types=1);

namespace App\Controller\students;

use App\Service\Interfaces\StudentQueryInterface;
use App\Service\StudentQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudentsByuserIdController extends AbstractController
{
    #[Route('/api/users/students', name: 'get_user_students', methods: ['GET'])]
    public function getUserStudents(StudentQueryInterface $studentQueryService, Request $request): JsonResponse
    {
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);
        $name = $request->query->get('name');
        $semesterId = $request->query->get('semester_id');
        $id= $request->query->get('user_id');
//        dd($semesterId);
        $students = $studentQueryService->findByUser($id, $limit, $page, $name, $semesterId);
        return $this->json($students);
    }
}

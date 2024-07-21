<?php

namespace App\Controller\students;

use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NoOfStudentsController extends AbstractController
{
    public function __construct(private StudentRepository $studentRepository)
    {

    }
    #[Route('api/students/total', name: 'app_no_of_students',methods: ['GET'])]
    public function index(): JsonResponse
    {
        $totalStudents = $this->studentRepository->count([]);
        $data = [
            'numberOfStudents' => $totalStudents, // Example number
        ];

        return new JsonResponse($data);
    }
}

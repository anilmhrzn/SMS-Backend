<?php

namespace App\Controller\students;

use App\Service\Interfaces\StudentQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ViewStudentById extends AbstractController
{
    #[Route('/api/students/{id}', methods: ['GET'])]
    public function view($id, StudentQueryInterface $studentQuery): JsonResponse
    {
        $data=$studentQuery->findByStudentId($id);
        return new JsonResponse($data);

    }
}
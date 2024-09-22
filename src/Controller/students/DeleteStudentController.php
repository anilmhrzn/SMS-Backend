<?php

declare(strict_types=1);

namespace App\Controller\students;

use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteStudentController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private StudentRepository $studentRepository;

    public function __construct(EntityManagerInterface $entityManager, StudentRepository $studentRepository)
    {
        $this->entityManager = $entityManager;
        $this->studentRepository = $studentRepository;
    }

    #[Route('/api/delete-student', methods: ['POST'])]
    public function delete(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $studentId = $data['id'] ?? null;

        if (!$studentId) {
            return new JsonResponse(['error' => 'Student ID is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $student = $this->studentRepository->find($studentId);

        if (!$student) {
            return new JsonResponse(['error' => 'Student not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $student->setDeleted(true);
        $this->entityManager->persist($student);
        $this->entityManager->flush();

        return new JsonResponse(['success' => 'Student marked as deleted']);
    }
}
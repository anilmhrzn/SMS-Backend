<?php

declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CountAllTeacherController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Route('/api/count-all-teacher')]
    public function index(): JsonResponse
    {
        $count = $this->userRepository->countByRoleAndSemester(null);

        return new JsonResponse($count, Response::HTTP_OK);
    }
}

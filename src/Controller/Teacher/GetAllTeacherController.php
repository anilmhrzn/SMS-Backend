<?php

declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Repository\UserRepository;
use App\Service\Teacher\GetAllTeachers\GetAllTeachersInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetAllTeacherController extends AbstractController
{
    public function __construct(private readonly GetAllTeachersInterface $getAllTeachers)
    {
    }

    #[Route('/api/get-all-teacher')]
    public function index(Request $request): JsonResponse
    {
        $semester = $request->query->get('semester');
        $limit = (int)$request->query->get('limit');
        $page = (int)$request->query->get('page');
        $users = $this->getAllTeachers->getTeachers($semester ?? null, $page  , $limit );
        return new JsonResponse($users, Response::HTTP_OK);
    }

}

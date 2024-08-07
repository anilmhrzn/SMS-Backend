<?php

declare(strict_types=1);

namespace App\Controller\semester;

use App\Service\Interfaces\SemesterQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewAllSemesterController extends AbstractController
{
    public function __construct(private readonly SemesterQueryInterface $semesterQuery)
    {
    }

    #[Route('api/view-all-semester')]
    public function index(): JsonResponse
    {
        $semester = $this->semesterQuery->getAllSemester();
        return new JsonResponse($semester, Response::HTTP_OK);
    }
}

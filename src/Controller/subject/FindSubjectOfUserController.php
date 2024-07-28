<?php

declare(strict_types=1);

namespace App\Controller\subject;

use App\Service\Interfaces\UserQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class FindSubjectOfUserController extends AbstractController
{
    public function __construct(private UserQueryInterface $userQuery)
    {
    }

    #[Route('/api/find-subject-of-user')]
    public function index(): JsonResponse
    {
        $response=$this->userQuery->findSubjectById($this->getUser()->getId());

        return new JsonResponse($response);
    }
}

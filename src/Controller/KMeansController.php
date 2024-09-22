<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class KMeansController extends AbstractController
{
    #[Route('/k-means')]
    public function index(): JsonResponse
    {
        return new JsonResponse('
        data');
    }
}

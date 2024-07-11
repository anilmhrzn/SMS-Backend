<?php

namespace App\Controller\subject;

use App\Service\SubjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ViewAllSubjectsController extends AbstractController
{
    public function __construct(public SubjectService $subjectService, private SerializerInterface $serializer)
    {

    }

    #[Route('api/subjects', name: 'add_subject', methods: ['GET'])]
    public function viewAll(Request $request)
    {
        $subjects = $this->subjectService->findAll();
        $jsonSubjects = $this->serializer->serialize($subjects, 'json', [
            AbstractNormalizer::ATTRIBUTES => ['id', 'name']
        ]);
        return new JsonResponse($jsonSubjects, Response::HTTP_OK, ['content-type' => 'application/json']);

    }
}

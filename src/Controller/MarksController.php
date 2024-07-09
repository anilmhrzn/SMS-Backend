<?php

namespace App\Controller;

use App\Dto\AddNewMark;
use App\Repository\MarksRepository;
use App\Service\MarksService;
use Cassandra\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MarksController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator, private MarksService $marksService,private MarksRepository $marksRepository)
    {

    }

    #[Route('/api/marks/new', name: 'add_mark', methods: ['POST'])]
    public function addMark(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            $dto = $this->validationWithDto($data);
            $mark = $this->marksService->addMark($dto);
        } catch (ValidationException $e) {
            return new JsonResponse(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\TypeError $e) {
            return new JsonResponse(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse($mark, Response::HTTP_CREATED);
    }

    #[Route('/api/marks/view', name: 'view_marks', methods: ['get'])]
    public function viewMarks(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $marksData=$this->marksService->getMarksById($data);
        return new JsonResponse($marksData, Response::HTTP_OK);
    }

    public function validationWithDto($data): AddNewMark
    {
        $dto = new AddNewMark(
            $data['student_id'],
            $data['exam_id'],
            $data['mark_obtained']
        );


        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new \Exception(json_encode(['errors' => $errorMessages]));
        }
        return $dto;

    }
}
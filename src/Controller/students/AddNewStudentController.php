<?php

namespace App\Controller\students;

use App\Dto\AddNewStudentRequest;
use App\Service\Interfaces\StudentManagementInterface;
use App\Service\Interfaces\UserInfoValidatorInterface;
use App\Service\PhotoProcessor;
use Cassandra\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddNewStudentController extends AbstractController
{
    public function __construct(private readonly UserInfoValidatorInterface $userInfoValidator, private readonly StudentManagementInterface $studentManagementInterface, private PhotoProcessor $photoProcessor)
    {
    }

    #[Route('api/student/new', name: 'app_student_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            $fileDetails = $this->photoProcessor->processPhoto($data['photo'], $this->getParameter('photos_directory'));
            $data['photo'] = $fileDetails['filename'];
            $dto = new AddNewStudentRequest($data);
            $validationErrors = $this->userInfoValidator->validate($dto);
            if (!empty($validationErrors)) {
                return new JsonResponse(['errors' => $validationErrors], Response::HTTP_BAD_REQUEST);
            }
            $student = $this->studentManagementInterface->createStudent($dto);
            $this->photoProcessor->moveFile($fileDetails['filePath'], $fileDetails['imageData']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($student, Response::HTTP_CREATED);
    }
}
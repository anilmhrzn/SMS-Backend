<?php

namespace App\Controller\marks;

use App\Dto\MarksUploadDTO;
use App\Service\Interfaces\CSVInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddMarksOfAExamController extends AbstractController
{

    public function __construct(private readonly CSVInterface $csvService, private readonly ValidatorInterface $validator)
    {
    }

    #[Route('/api/add/marks/of-exam', name: 'app_add_marks_of_a_exam', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $dto = new MarksUploadDTO($request->request->all() ?: [], $request->files->get('csv_file'));
        $errors = $this->validator->validate($dto);
        $errorMessages = [];
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $message = str_replace('{{ value }}', $error->getInvalidValue(), $error->getMessageTemplate());
                $errorMessages[] = $message;
            }
            return $this->json(['errors' => $errorMessages]);
        }
        try {
            $ignoredRecords = $this->csvService->validateAndParseCSV($dto, ['StudentID', 'Marks']);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        return $this->json([
            'message' => 'Marks added successfully',
            'error' => $ignoredRecords
        ], Response::HTTP_OK);
    }
}
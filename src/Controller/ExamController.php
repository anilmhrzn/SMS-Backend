<?php

namespace App\Controller;

use App\Dto\AddExamRequest;
use App\Entity\Exam;
use App\Service\ExamService;
//use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;
//use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;


class ExamController extends AbstractController
{
    public function __construct(public ExamService $examService)
    {

    }

    #[Route('/api/exams/new', name: 'add_exam', methods: ['POST'])]
    public function addExam(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);
        $examRequest = new AddExamRequest(new DateTime($data['date']), $data['name'], $data['semester']);
        $errors = $validator->validate($examRequest);
        if (count($errors) > 0) {
            $data = [];
            foreach ($errors as $error) {
                $data[] = $error->getMessage();
            }
            return new JsonResponse(["errors" => $data], Response::HTTP_BAD_REQUEST, ['content-type' => 'application/json']);
        }
//        TODO: do this
//        dd($examRequest);
        $exam = $this->examService->createExam($examRequest);


        return new JsonResponse($exam, Response::HTTP_CREATED);
    }
}

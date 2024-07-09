<?php


namespace App\Controller;

use App\Dto\AddAttendanceRequest;
use App\Service\AddAttendanceService;
use App\Service\ExamService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AttendanceController extends AbstractController
{

    public function __construct(public ExamService $examService, private ValidatorInterface $validator, public AddAttendanceService $addAttendanceService)
    {

    }

    #[Route('api/attendance/new', name: 'add_attendance', methods: ['POST'])]
    public function addAttendance(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $dto = new AddAttendanceRequest($data['studentId'], new DateTime($data['date']), $data['status'], $data['userId']);
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $data = [];
            foreach ($errors as $error) {
                $data[] = $error->getMessage();
                dump($error->getMessage(), $error->getPropertyPath());

            }
            return new JsonResponse(["errors" => $data], Response::HTTP_BAD_REQUEST, ['content-type' => 'application/json']);
        }

        $attendance = $this->addAttendanceService->createAttendance($dto);
        return new JsonResponse($attendance, Response::HTTP_CREATED);

    }
//Todo: do the view for attendance

}

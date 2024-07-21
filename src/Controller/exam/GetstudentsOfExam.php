<?php


namespace App\Controller\exam;
use App\Service\SubjectService;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GetstudentsOfExam extends AbstractController
{

    public function __construct(public SubjectService $subjectService, public SerializerInterface $serializer)
    {

    }

    #[Route('api/exam/students', name: 'students-of-the-exam', methods: ['GET'])]
    public function studentsOfTheExam(Request $request)
    {
        $students=$this->subjectService->findStudentsByExamID($request->query->get('examId'));
//        dd($students)
        $csv = Writer::createFromString('');

        $csv->insertOne(['StudentID', 'Name', 'Email', 'Marks']);

        // Step 4: Prepare and Insert Data Rows
        $data = [];
        foreach ($students as $student) {
            $data[] = [$student->getId(), $student->getName(), $student->getEmail(), "N/A"];
        }
        $csv->insertAll($data);

        // Step 5: Set Response Headers
        $response = new StreamedResponse(function() use ($csv) {
            echo $csv->getContent();
        });
        $response->headers->set('Content-Type', 'text/csv');
        $filename = "students_" . date('Ymd') . ".csv";
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Step 6: Return the Response
        return $response;
//        return new JsonResponse($dataJson, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

}

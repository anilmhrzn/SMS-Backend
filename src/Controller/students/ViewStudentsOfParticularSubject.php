<?php


namespace App\Controller\students;

use App\Service\SubjectService;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

class ViewStudentsOfParticularSubject extends AbstractController
{
    public function __construct(public SubjectService $subjectService, )
    {

    }


    #[Route('api/students-from-subject', name: 'viewStudentsOfSubject', methods: ['GET'])]
    public function viewStudentsOfSubject(Request $request): Response
    {
        // Step 1: Fetch data
        $students = $this->subjectService->getStudentsForSubject($request->query->get('subjectId'));

        // Step 2: Create a CSV Writer instance
        $csv = Writer::createFromString('');

        // Step 3: Insert CSV Header
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
    }
}

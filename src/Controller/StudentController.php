<?php

namespace App\Controller;

use App\Dto\AddNewStudentRequest;
use App\Repository\StudentRepository;
use App\Service\PhotoProcessor;
use App\Service\StudentService;
use App\Service\Trie_based_search\StudentTrieService;
use Cassandra\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api')]
class StudentController extends AbstractController
{
    public function __construct(private StudentService $studentService, private PhotoProcessor $photoProcessor)
    {
    }

    #[Route('/students-of-user', name: 'app_student_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        $userId=  $this->getUser()->getId();
//        dd($userId);
        $studentsArray = $this->studentService->findByUser($userId,$limit,$page);
//        dd($studentsArray);
        $totalStudents = $this->studentService->getTotalStudentsCountOfUser($userId);

//        dd('here');

        return new JsonResponse([
            'students' => $studentsArray,
            'total' => $totalStudents,
            'page' => $page,
            'limit' => $limit
        ]);
    }
    #[Route('/students', name: 'app_student_index_all', methods: ['GET'])]
    public function indexall( Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        $studentsArray = $this->studentService->findAllByLimitAndPage($limit,$page);
        $totalStudents = $this->studentService->getTotalStudentsCount(); // Get the total number of students

        return new JsonResponse([
            'students' => $studentsArray,
            'total' => $totalStudents,
            'page' => $page,
            'limit' => $limit
        ]);
    }

    #[Route('/student/view', name: 'app_student_index_by_user', methods: ['GET'])]
    public function indexByUser(StudentRepository $studentRepository, Request $request): Response
    {
        $studentId = $request->query->get('student_id');
        $studentsArray = $this->studentService->findByStudentId($studentId);
        return new JsonResponse($studentsArray);
    }
    #[Route('/student/new', name: 'app_student_new', methods: ['POST'])]
    public function new(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            $fileDetails = $this->photoProcessor->processPhoto($data['photo'], $this->getParameter('photos_directory'));
            $data['photo'] = $fileDetails['filename'];
            $dto = $this->createAndValidateDto($data, $validator);
            $student = $this->studentService->createStudent($dto);
            $this->photoProcessor->moveFile($fileDetails['filePath'], $fileDetails['imageData']);
        } catch (ValidationException $e) {
            return new JsonResponse(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($student, Response::HTTP_CREATED);
    }
    #[Route('/student/addtoteacher/{id}', name: 'app_student_show', methods: ['GET'])]
    public function show(StudentRepository $studentRepository, $id): Response
    {
        $student = $studentRepository->find($id);
        return new JsonResponse($student);
    }
    #[Route('/student/autocomplete', name: 'student_autocomplete', methods: ['GET'])]
    public function autocomplete(Request $request, StudentTrieService $studentTrieService): JsonResponse
    {

        $query = strtolower($request->query->get('query', ''));
        $students = $studentTrieService->searchStudents($query);

        $results = array_map(function ($student) {
            return [
                'id' => $student->getId(),
                'name' => $student->getName(),
//                'email' => $student->getEmail(),
            ];
        }, $students);

        return $this->json($results);
    }

    private function createAndValidateDto(array $data, ValidatorInterface $validator): AddNewStudentRequest
    {
        $dto = new AddNewStudentRequest(
            $data['name'],
            $data['email'],
            $data['photo'],
            $data['gender'],
            $data['number']
        );

        $errors = $validator->validate($dto);
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
//TODO attendace complete garerw matrw aru gara


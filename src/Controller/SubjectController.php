<?php
namespace App\Controller;
use App\Dto\AddNewSubjectRequest;
use App\Service\SubjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubjectController extends AbstractController
{
    public function __construct(public SubjectService $subjectService, private ValidatorInterface $validator)
    {

    }
    #[Route('api/admin/subject/new', name: 'add_subject', methods: ['POST'])]
    public function addsubject(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $dto = new AddNewSubjectRequest($data['name']);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $data = [];
            foreach ($errors as $error) {
                $data[] = $error->getMessage();
                dump($error->getMessage(), $error->getPropertyPath());

            }
            return new JsonResponse(["errors" => $data], Response::HTTP_BAD_REQUEST, ['content-type' => 'application/json']);
        }
        $subject= $this->subjectService->createSubject($dto);
        $subjectData = [
            'id' => $subject->getId(),
            'name' => $subject->getName(),
        ];
        return new JsonResponse($subjectData, Response::HTTP_CREATED);

    }
}

<?php

namespace App\Controller;

use App\Dto\AddNewSubjectRequest;
use App\Dto\RegistrationRequest;
use App\Entity\Subject;
use App\Entity\User;
use App\Service\SubjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {

    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, SubjectService $subjectService): JsonResponse
    {
        $data = $request->request->all();

        $dto = $this->validateUsingDto($data);
        $user = $this->createUser($dto, $passwordHasher);
        $subject = $this->createSubject($data['subject'], $subjectService);

        $user->setSubject($subject);
        $entityManager->persist($user);
        $entityManager->flush();

        $userData = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'subject' => $subject->getName(),
        ];

        return $this->json([$userData], Response::HTTP_CREATED);
    }

    public function validateUsingDto($data)
    {
        $dto = new RegistrationRequest($data['name'], $data['email'], $data['password'], $data['subject']);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }
        return $dto;
    }

    private function createUser(RegistrationRequest $dto, UserPasswordHasherInterface $passwordHasher): User
    {
        $user = new User();
        $user->setName($dto->getName());
        $user->setEmail($dto->getEmail());
        $user->setPassword($passwordHasher->hashPassword($user, $dto->getPassword()));

        return $user;
    }

    private function createSubject(string $subjectName, SubjectService $subjectService): Subject
    {
        $subjectDto = new AddNewSubjectRequest($subjectName);
        return $subjectService->createSubject($subjectDto);
    }
}
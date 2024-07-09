<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
//TODO complete the code
#[Route('api')]
class UserController extends AbstractController
{
    public function __construct(private UserService $userService)
    {

    }
    #[Route('/admin/addStudentToUser', name: 'app_user_index', methods: ['post'])]
    public function view(Request $request): JsonResponse
    {
        $data=json_decode($request->getContent(),true);
        $this->userService->addStudentToUserAndSubjectToStudent($data['userId'],$data['studentId']);
        return new JsonResponse(['message' => 'student added to user successfully']);
    }

}
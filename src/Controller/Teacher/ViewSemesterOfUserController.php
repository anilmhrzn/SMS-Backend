<?php

declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewSemesterOfUserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Route('/api/view-semester-of-user')]
    public function index(): JsonResponse
    {
        $id = $this->getUser()->getId();
//        dd($id);
        $user=$this->userRepository->findOneBy(['id'=>$id]);
        $userSemesters = $user->getSemester();
//        dd($userSemesters->getSemester());
        return new JsonResponse(['userSemesters'=>$userSemesters->getSemester()]);

    }
}

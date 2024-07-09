<?php

namespace App\Service;

use App\Repository\StudentRepositoryInterface;
use App\Repository\UserRepositoryInterface;

class UserService
{

    public function __construct(private StudentRepositoryInterface $studentRepository, private UserRepositoryInterface $userRepository)
    {
    }
//    public function createUser($dto)
//    {
//    }
//    public function getUser($id)
//    {
//    }
//    public function updateUser($id, $dto)
//    {
//        //TODO complete the code
//    }
//    public function deleteUser($id)
//    {
//        //TODO complete the code
//    }
    public function addStudentToUserAndSubjectToStudent($userId, $StudentId)
    {
        $student = $this->studentRepository->findById($StudentId);
        $user=$this->userRepository->addStudentToUser($userId, $student);
        $userSubject=$user->getSubject();
        $this->studentRepository->addSubjectTostudent($StudentId,$userSubject);
    }

//    public function addSubjectToStudent($StudentId, $subjectId)
//    {
//        $this->studentRepository->addSubjectTostudent($StudentId, $subjectId);
//
//    }

//    public function addStudentToUser($userId, $StudentId)
//    {
//        $student = $this->studentRepository->findById($StudentId);
//        $this->userRepository->addStudentToUser($userId, $student);
//
////        $this->studentRepository->addSubjectTostudent($StudentId);
//    }

}
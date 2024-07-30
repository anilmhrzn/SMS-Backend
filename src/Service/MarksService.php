<?php

namespace App\Service;

use App\Dto\AddNewMark;
use App\Entity\Marks;
use App\Repository\ExamRepository;
use App\Repository\MarksRepository;
use App\Repository\MarksRepositoryInterface;
use App\Repository\StudentRepository;

class MarksService
{

    public function __construct(private StudentRepository $studentRepository, private ExamRepository $examRepository, private MarksRepositoryInterface $marksRepository)
    {
    }

    public function addMark(AddNewMark $mark): array
    {
        $markEntity = new Marks();
        $student = $this->studentRepository->findById($mark->getStudent());
        if (!$student) {
            throw new \Exception("Student not found");
        }
        $exam = $this->examRepository->findById($mark->getExam());
        if (!$exam) {
            throw new \Exception("Exam not found");
        }
        $markEntity->setStudent($student);
        $markEntity->setExam($exam);
        $markEntity->setMarkObtained($mark->getMark());
        $this->marksRepository->addMark($markEntity);
        $markArray = [
            'id' => $markEntity->getId(),
            'student' => $markEntity->getStudent()->getId(),
            'exam' => $markEntity->getExam()->getId(),
            'mark' => $markEntity->getMarkObtained()
        ];
                                                                                                                        return $markArray;

    }

    public function getMarksById($data): array
    {
        $marksArray=$this->marksRepository->viewMarks($data);
        $marksData = [];
        foreach ($marksArray as $marks) {
            $marksData[] = [
                'id' => $marks->getId(),
                'mark_obtained' => $marks->getMarkObtained(),
                'student_id' => $marks->getStudent()->getId(),
                'exam_id' => $marks->getExam()->getName(),
            ];
        }
        return $marksData;
    }

}
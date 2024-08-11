<?php

namespace App\Service;

use App\Repository\ExamRepositoryInterface;
use App\Repository\SemesterRepositoryInterface;
use App\Repository\SubjectRepository;
use App\Service\Interfaces\ExamQueryInterface;

readonly class ExamQueryService implements ExamQueryInterface
{
    public function __construct(private ExamRepositoryInterface $examRepository, private SubjectRepository $subRepo, private SemesterRepositoryInterface $semesterRepository)
    {
    }

    public function findAllByLimitAndPage($limit, $page): array
    {
        $exams = $this->examRepository->findAllByLimitAndPage($limit, $page);
        $examsArray = [];
        foreach ($exams as $exam) {
            $examsArray[] = [
                'id' => $exam->getId(),
                'name' => $exam->getName(),
                'date' => $exam->getDate()->format('Y-m-d'),
//                'subject'=>$exam->getSubject()->getName(),
            ];
        }
        $totalExams = count($examsArray);
        return [
            'exams' => $examsArray,
            'total' => $totalExams,
            'page' => $page,
            'limit' => $limit
        ];
    }

    public function searchExam(?int $id, ?string $name, ?string $date, ?int $semester, $limit, $page): array
    {
        $id = $id !== null ? (int)$id : null;

//        if ($sub !== null && $sub !== 0){
//            $subId = $this->subRepo->findById($sub);
//
//            if ($subId == null) {
//                throw new \Exception('Subject not found');
//            }
//
//        }else{
//            $subId = null;
//        }
//        if($se)
        if ($semester !== null && $semester !== 0) {
            $semId = $this->semesterRepository->findById($semester);
            if ($semId == null) {
                throw new \Exception('Semester not found');
            }
        } else {
            $semId = null;
        }

        $exams = $this->examRepository->findByIdAndOrNameOrDateOrSub($id, $name, $date, $semId, $limit, $page);
        $data = [];
        foreach ($exams as $exam) {
            $data[] = [
                'id' => $exam->getId(),
                'name' => $exam->getName(),
                'date' => $exam->getDate()->format('Y-m-d'),
                'semester' => $exam->getSemester()->getSemester(),
            ];
        }
        $totalExams = count($data);
        return [
            'exams' => $data,
            'total' => $totalExams,
            'page' => $page,
            'limit' => $limit
        ];
//        return $data;
    }
}
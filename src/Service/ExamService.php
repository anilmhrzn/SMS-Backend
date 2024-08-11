<?php

namespace App\Service;

use App\Dto\AddExamRequest;
use App\Entity\Exam;
use App\Repository\ExamRepositoryInterface;
use App\Repository\SemesterRepositoryInterface;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class ExamService
{
    public function __construct(private ExamRepositoryInterface $examRepository, private EntityManagerInterface $entityManager, private SemesterRepositoryInterface $semesterRepository)
    {
    }

    public function createExam(AddExamRequest $data): array
    {

        $exam = new Exam();
        $semester = $this->semesterRepository->findById($data->getSemester());
//        $subId = $this->subRepo->findById($data->getSubject());
        if ($semester == null) {
            throw new \Exception('Semester not found');
        }
//        dd($semester);

        $exam->setFromDto($data, $semester);
        $this->entityManager->beginTransaction();
        try {
            $this->examRepository->addNewExam($exam);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        $examData = [
            'id' => $exam->getId(),
            'name' => $exam->getName(),
            'date' => $exam->getDate()->format('Y-m-d'),
//            'subject' => $exam->getSubject()->getName(),
//             'time' => $exam->getTime(),
//            'duration' => $exam->getDuration(),
//            'total_marks' => $exam->getTotalMarks(),
        ];
        return $examData;
    }

//    public function searchExam(?int $id, ?string $name, ?string $date, ?int $sub): array
//    {
//        if ($sub !== null && $sub !== 0){
//            $subId = $this->subRepo->findById($sub);
//            if ($subId == null) {
//                throw new \Exception('Subject not found');
//            }
//
//        }else{
//            $subId = null;
//        }
//
//
//        $exams = $this->examRepository->findByIdAndOrNameOrDateOrSub($id, $name, $date, $subId);
//        $data = [];
//        foreach ($exams as $exam) {
//            $data[] = [
//                'id' => $exam->getId(),
//                'name' => $exam->getName(),
//                'date' => $exam->getDate()->format('Y-m-d'),
//                'subject' => $exam->getSubject()->getName()
//            ];
//        }
//        return $data;
//    }
}

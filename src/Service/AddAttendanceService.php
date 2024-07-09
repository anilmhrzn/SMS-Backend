<?php
namespace App\Service;
use App\Dto\AddAttendanceRequest;
use App\Entity\Attendance;
use App\Repository\AttendanceRepository;
use App\Repository\StudentRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class AddAttendanceService
{
    public function __construct(private StudentRepositoryInterface $studentRepository,private UserRepositoryInterface $userRepository, private EntityManagerInterface $entityManager, private AttendanceRepository $attendanceRepository)
    {
    }
    public function createAttendance(AddAttendanceRequest $data): array
    {
        $attendance=new Attendance();
        $studId= $this->studentRepository->findById($data->getStudentId());
        $userId= $this->userRepository->findById($data->getUserId());
        if ($studId == null) {
            throw new \Exception('Subject not found');
        }
        if ($userId == null) {
            throw new \Exception('user not found');
        }
        $attendance->setFromDto($data, $studId,$userId);

        $this->entityManager->beginTransaction();
        try {
            $this->attendanceRepository->addAttendance($attendance);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        $attendanceData = [
            'id' => $attendance->getId(),
            'date' => $attendance->getDate()->format('Y-m-d'),
            'student' => $attendance->getStudent()->getName(),
            'status' => $attendance->isStatus(),
            'user' => $attendance->getUser()->getName(),
        ];
        return $attendanceData;
    }

}
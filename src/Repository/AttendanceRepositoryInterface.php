<?php
namespace App\Repository;
use App\Entity\Attendance;

interface AttendanceRepositoryInterface
{
    public function addAttendance(Attendance $attendance): void;

}
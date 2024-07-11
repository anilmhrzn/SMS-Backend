<?php
namespace App\Repository;
use App\Entity\Subject;

interface SubjectRepositoryInterface
{
    public function addNewSubject(Subject $subject): void;
    public function findAll(): array;
}
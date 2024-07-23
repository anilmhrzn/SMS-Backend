<?php

namespace App\Repository;

use App\Entity\Marks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Marks>
 */
class MarksRepository extends ServiceEntityRepository implements MarksRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marks::class);
    }

    //    /**
    //     * @return Marks[] Returns an array of Marks objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Marks
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function addMarks(array $marks): void
    {

        // TODO: Implement addMarks() method.
    }

    public function addMark(Marks $mark): void
    {
        $em = $this->getEntityManager();
        $em->persist($mark);
        $em->flush();
        // TODO: Implement addMark() method.
    }

    public function viewMarks(array $data): array
    {
//        $em= $this->getEntityManager();
        $qb = $this->createQueryBuilder('m')
            ->select('m', 'e')
            ->leftJoin('m.exam', 'e')
            ->where('m.student = :student_id')
            ->setParameter('student_id', $data['student_id'])
            ->andwhere('e.subject = :subject_id')
            ->setParameter('subject_id', $data['subject_id'])
            ->getQuery()
            ->getResult();
        return $qb;
        // TODO: Implement viewMarks() method.
    }

   public function findMarksByExamId(int $examId)
{
    $qb = $this->createQueryBuilder('m')
        ->select('m.mark_obtained', 's.id AS student_Id', 's.name AS student_name')
        ->join('m.student', 's')
        ->where('m.exam = :examId')
        ->setParameter('examId', $examId)
        ->getQuery();

    return $qb->getResult();
}
function countFailedStudentsByExamId(int $examId): int
{
    $qb = $this->createQueryBuilder('m')
        ->select('COUNT(m.id)')
        ->where('m.exam = :examId')
        ->andWhere('m.mark_obtained < 40')
        ->setParameter('examId', $examId)
        ->getQuery();

    return $qb->getSingleScalarResult();
}
}

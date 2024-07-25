<?php

namespace App\Repository;

use App\Entity\Exam;
use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class ExamRepository extends ServiceEntityRepository implements ExamRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exam::class);
    }

    //    /**
    //     * @return Exam[] Returns an array of Exam objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Exam
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findById(int $id): ?Exam
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function addNewExam(Exam $exam): void
    {
        $em = $this->getEntityManager();
        $em->persist($exam);
        $em->flush();
    }

    public function findByIdAndOrNameOrDateOrSub(?int $id, ?string $name, ?string $date, ?Subject $subject, $limit, $page): Paginator
    {
        $qb = $this->createQueryBuilder('e');

        if ($id !== null) {
            $qb->andWhere('e.id = :id')
                ->setParameter('id', $id);
        }

        if ($name !== null && $name !== '') {
            $qb->andWhere('LOWER(e.name) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $name . '%');
        }
//        dd('here', $date);
        if ($date !== null && $date !== '') {
//            dd('here', $date);
//            $date= new \DateTime($date);
            $date = new \DateTime($date);
            $qb->andWhere('e.date = :date')
                ->setParameter('date', $date);
        }
        if ($subject !== null) {
            $qb->andWhere('e.subject = :subject')
                ->setParameter('subject', $subject);
        }
        $qb->getQuery();
        $paginator = new Paginator($qb);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit);
        return $paginator;
    }

    public function findLatestTakenExam(): ?Exam
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.date', 'DESC') // Order by date in descending order
            ->setMaxResults(1) // Limit to only the most recent exam
            ->getQuery()
            ->getOneOrNullResult(); // Execute the query and return the result or null
    }

    public function findComingExams(\DateTime $today, \DateTime|false $futureDate)
    {
        return $this->createQueryBuilder('e')
            ->where('e.date BETWEEN :today AND :futureDate')
            ->setParameter('today', $today)
            ->setParameter('futureDate', $futureDate)
            ->getQuery()
            ->getResult();
    }

    public function countComingExams(\DateTime $today, \DateTime|false $futureDate)
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.date BETWEEN :today AND :futureDate')
            ->setParameter('today', $today)
            ->setParameter('futureDate', $futureDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findStudentIsAllowedToGiveExam($studentId, $examId)
    {
        $qb = $this->createQueryBuilder('e')
            ->innerJoin('e.subject', 's')
            ->innerJoin('s.students', 'st')
            ->where('e.id = :examId')
            ->andWhere('st.id = :studentId')
            ->setParameter('examId', $examId)
            ->setParameter('studentId', $studentId)
            ->getQuery();

        return $qb->getOneOrNullResult();
    }

    public function findAllByLimitAndPage($limit, $page): Paginator
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.date', 'DESC')
            ->getQuery();
        $paginator = new Paginator($qb);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit);
        return $paginator;
    }
}

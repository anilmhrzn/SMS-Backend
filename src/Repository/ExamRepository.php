<?php

namespace App\Repository;

use App\Entity\Exam;
use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findByIdAndOrNameOrDateOrSub(?int $id, ?string $name, ?String $date, ?Subject $subject): array
    {
        $qb = $this->createQueryBuilder('e');

        if ($id !== null) {
            $qb->andWhere('e.id = :id')
                ->setParameter('id', $id);
        }

        if ($name !== null && $name !== ''){
            $qb->andWhere('LOWER(e.name) LIKE LOWER(:name)')
                ->setParameter('name', '%'.$name.'%');
        }
//        dd('here', $date);
        if ($date !== null && $date !== ''){
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
        return $qb->getQuery()->getResult();
    }
}

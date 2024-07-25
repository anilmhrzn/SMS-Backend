<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Student>
 */
class StudentRepository extends ServiceEntityRepository implements StudentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    //    /**
    //     * @return Student[] Returns an array of Student objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Student
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findById(int $id): ?Student
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function addNewStudent(Student $student): void
    {
        $em = $this->getEntityManager();
        $em->persist($student);
        $em->flush();
    }

    public function findByUser($userId, $limit = null, $offset = null)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

//    public function findOneByUId( $studentId)
//{
//    return $this->createQueryBuilder('s')
//        ->andWhere('s.id=:studentId')
//        ->setParameter('studentId', $studentId)
//        ->getQuery()
//        ->getResult();
//}
    public function addSubjectTostudent($studentId,$subjectId)
    {
        $student = $this->findById($studentId);
        $subject = $this->getEntityManager()->getRepository(Subject::class)->find($subjectId);
        $student->addSubject($subject);
        $this->getEntityManager()->persist($student);
        $this->getEntityManager()->flush();
    }

    public function findAllByLimitAndPage($limit , $page): Paginator
    {
//        dd($limit,$page);
        $queryBuilder = $this->createQueryBuilder('s')
            ->orderBy('s.id', 'ASC')
            ->getQuery();

        $paginator = new Paginator($queryBuilder);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit);
//        dd($paginator);
        return $paginator;
    }
    public function countStudentsOfUser(int $userId): int
    {
        $qb = $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->innerJoin('s.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}

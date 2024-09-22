<?php

namespace App\Repository;

use App\Entity\Semester;
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
    public function addSubjectTostudent($studentId, $subjectId)
    {
        $student = $this->findById($studentId);
        $subject = $this->getEntityManager()->getRepository(Subject::class)->find($subjectId);
        $student->addSubject($subject);
        $this->getEntityManager()->persist($student);
        $this->getEntityManager()->flush();
    }

    public function findAllByLimitAndPage($limit, $page): Paginator
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

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function findBySemesterOrName(?string $name, ?Semester $semester, $limit, $page): Paginator
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.is_deleted = false');

        if ($name !== null && $name !== '') {
            $qb->andWhere('LOWER(s.name) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $name . '%');
        }
        if ($semester !== null) {
            $qb->andWhere('s.semester = :semester')
                ->setParameter('semester', $semester);
        }
        $qb->getQuery();
        $qb->orderBy('s.id', 'ASC');
//        dd($qb->getQuery()->getResult());
        $paginator = new Paginator($qb);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit);
        return $paginator;
    }

    public function countFindBySemesterOrName(?string $name, ?Semester $semester): int
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('count(s.id)');
        if ($name !== null && $name !== '') {
            $qb->andWhere('LOWER(s.name) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $name . '%');
        }
        if ($semester !== null) {
            $qb->andWhere('s.semester = :semester')
                ->setParameter('semester', $semester);
        }
//        dd($qb->getQuery());
        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function findByUserWithFilters(int $userId, ?string $name, ?int $semesterId, int $limit, int $page): Paginator
    {
        $offset = ($page - 1) * $limit;
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.users', 'u')
            ->addSelect('u')
            ->where('u.id = :userId')
            ->andWhere('s.is_deleted = false')
            ->setParameter('userId', $userId);

        if ($name !== null && $name !== '') {
            $qb->andWhere('LOWER(s.name) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($semesterId !== null) {
            $qb->andWhere('s.semester = :semesterId')
                ->setParameter('semesterId', $semesterId);
        }

        $qb->setFirstResult($offset)
            ->setMaxResults($limit);
//        dd($qb->getQuery());
        return new Paginator($qb);
    }

    public function findStudentIsAllowedToGiveExam(mixed $studentId, $semesterId)
    {
        $student = $this->find($studentId);

        // If student is not found, return false
        if (!$student) {
            return false;
        }

        // Compare the student's semester with the provided semesterId
        if ($student->getSemester() && $student->getSemester()->getId() === $semesterId) {
            return true;
        }

        // If the semester does not match, return false
        return false;
    }
}
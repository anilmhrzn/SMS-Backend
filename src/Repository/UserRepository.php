<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findById(int $id): ?User
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function addStudentToUser(int $id, Student $student): User
    {
        $em = $this->getEntityManager();
        $user = $this->findById($id);
        $user->addStudent($student);
        $em->persist($user);
        $em->flush();
        return $user;
    }

    public function findSubjectByUser(int $id): Subject
    {
        $user = $this->findById($id);

        return $user->getSubject();
    }

    public function findByRole(): array
    {
        $role = 'ROLE_USER';
        return $this->createQueryBuilder('u')
            ->innerJoin('u.semester', 's')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"' . $role . '"%')
            ->getQuery()
            ->getResult();
    }

//    public function findByRoleAndSemester(?int $semesterNumber): array
//    {
//
//        $role = 'ROLE_USER';
//        $qb = $this->createQueryBuilder('u')
//
//            ->innerJoin('u.semester', 's')
//            ->andWhere('u.roles LIKE :role')
//            ->setParameter('role', '%"' . $role . '"%');
//        if ($semesterNumber != null) {
//            $qb->andWhere('s.semester = :semesterNumber')
//                ->setParameter('semesterNumber', $semesterNumber);
//        }
//        $qb->leftJoin('u.subject', 'sub');
//        return $qb->select('u.id,u.name, u.email,u.roles,s.semester,sub.name as subject')
//            ->getQuery()
//            ->getResult();
//    }

 public function findByRoleAndSemester(?int $semesterNumber, int $page = 1, int $limit = 10): Paginator
{
    $role = 'ROLE_USER';
    $qb = $this->createQueryBuilder('u')
        ->select('u, s.semester, sub.name as subject')
        ->innerJoin('u.semester', 's')
        ->leftJoin('u.subject', 'sub')
        ->andWhere('u.roles LIKE :role')
        ->setParameter('role', '%"' . $role . '"%');
    if ($semesterNumber != null) {
        $qb->andWhere('s.semester = :semesterNumber')
            ->setParameter('semesterNumber', $semesterNumber);
    }
    $qb->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit);
//    dd($qb->getQuery()->getResult());
    return new Paginator($qb);
}
}



//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

<?php

namespace App\DataFixtures;

use App\Entity\Semester;
use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {

        $semestersArray = [];
        $semesters = range(1, 8);
        foreach ($semesters as $semesterNumber) {
            $semester = new Semester();
            $semester->setSemester($semesterNumber);
            $manager->persist($semester);
            $semestersArray[] = $semester;
        }

        $subjects = [
            1 => ['Computer Fundamentals & Applications', 'Society & Technology', 'English I', 'Mathematics I', 'Digital Logic'],
            2 => ['Data Structures', 'Discrete Mathematics', 'English II', 'Mathematics II', 'Microprocessor'],
            3 => ['Database Management System', 'Operating System', 'Computer Networks', 'Software Engineering', 'Mathematics III'],
            4 => ['Object Oriented Programming', 'Web Technology', 'Artificial Intelligence', 'Compiler Design', 'Mathematics IV'],
            5 => ['Mobile Computing', 'Cloud Computing', 'Big Data', 'Cyber Security', 'Mathematics V'],
            6 => ['Machine Learning', 'Data Mining', 'Internet of Things', 'Blockchain Technology', 'Mathematics VI'],
            7 => ['Advanced Algorithms', 'Parallel Computing', 'Quantum Computing', 'Natural Language Processing', 'Mathematics VII'],
            8 => ['Project Management', 'Entrepreneurship', 'Ethics in IT', 'Research Methodology', 'Mathematics VIII']
        ];

        foreach ($subjects as $semesterNumber => $subjectNames) {
            foreach ($subjectNames as $subjectName) {
                $subject = new Subject();
                $subject->setName($subjectName);
                $subject->setSemester($semestersArray[$semesterNumber - 1]);
                $manager->persist($subject);
            }
        }


        $faker = Factory::create('ne_NP');

        for ($i = 0; $i < 80; $i++) {
            $student = new Student();
            $student->setName($faker->name);
            $student->setEmail($faker->email);
            $student->setGender($faker->randomElement(['Male', 'Female']));
            $student->setNumber([$faker->phoneNumber]);
            $student->setPhoto('test.jpeg');
            $number = ($i % 8) ;
            $student->setSemester(
                $semestersArray[$number]
            );
            $manager->persist($student);
        }

        $manager->persist($student);
        $user = new User();
        $user->setName('Admin');
        $user->setEmail('admin@gmail.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, '12341234'));
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);


        $manager->flush();
    }
}

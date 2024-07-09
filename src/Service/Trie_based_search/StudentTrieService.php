<?php
namespace App\Service\Trie_based_search;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class StudentTrieService
{
    private Trie $trie;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->trie = new Trie();
        $this->entityManager = $entityManager;
        $this->initializeTrie();
    }

    private function initializeTrie(): void
    {
        $students = $this->entityManager->getRepository(Student::class)->findAll();

        foreach ($students as $student) {
            $this->trie->insert($student->getName(), $student);
            $this->trie->insert($student->getEmail(), $student);
        }
    }

    public function searchStudents(string $query): array
    {
        return $this->trie->search($query);
    }

    public function addStudentToTrie(Student $student): void
    {
        $this->trie->insert($student->getName(), $student);
        $this->trie->insert($student->getEmail(), $student);
    }

    public function updateStudentInTrie(Student $student): void
    {
        // Update the Trie when student details change
//        $this->removeStudentFromTrie($student); // Assuming a remove method exists
        $this->addStudentToTrie($student);
    }
}

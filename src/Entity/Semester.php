<?php

namespace App\Entity;

use App\Repository\SemesterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemesterRepository::class)]
class Semester
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $semester = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'semester')]
    private Collection $user;

    /**
     * @var Collection<int, Subject>
     */
    #[ORM\OneToMany(targetEntity: Subject::class, mappedBy: 'semester')]
    private Collection $subject;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'semester')]
    private Collection $student;

    /**
     * @var Collection<int, Exam>
     */
    #[ORM\OneToMany(targetEntity: Exam::class, mappedBy: 'semester')]
    private Collection $exam;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->subject = new ArrayCollection();
        $this->student = new ArrayCollection();
        $this->exam = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSemester(): ?int
    {
        return $this->semester;
    }

    public function setSemester(int $semester): static
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setSemester($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSemester() === $this) {
                $user->setSemester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubject(): Collection
    {
        return $this->subject;
    }

    public function addSubject(Subject $subject): static
    {
        if (!$this->subject->contains($subject)) {
            $this->subject->add($subject);
            $subject->setSemester($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        if ($this->subject->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getSemester() === $this) {
                $subject->setSemester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
            $student->setSemester($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->student->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getSemester() === $this) {
                $student->setSemester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exam>
     */
    public function getExam(): Collection
    {
        return $this->exam;
    }

    public function addExam(Exam $exam): static
    {
        if (!$this->exam->contains($exam)) {
            $this->exam->add($exam);
            $exam->setSemester($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): static
    {
        if ($this->exam->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getSemester() === $this) {
                $exam->setSemester(null);
            }
        }

        return $this;
    }
}

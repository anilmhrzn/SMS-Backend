<?php

namespace App\Entity;

use App\Repository\MarksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarksRepository::class)]
class Marks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mark_obtained = null;

    /**
     * @var Collection<int, Subject>
     */
    #[ORM\OneToMany(targetEntity: Subject::class, mappedBy: 'marks')]
    private Collection $subject;

    #[ORM\ManyToOne(inversedBy: 'marks')]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'marks')]
    private ?Exam $exam = null;

    public function __construct()
    {
        $this->subject = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarkObtained(): ?string
    {
        return $this->mark_obtained;
    }

    public function setMarkObtained(string $mark_obtained): static
    {
        $this->mark_obtained = $mark_obtained;

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
            $subject->setMarks($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        if ($this->subject->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getMarks() === $this) {
                $subject->setMarks(null);
            }
        }

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): static
    {
        $this->exam = $exam;

        return $this;
    }
}

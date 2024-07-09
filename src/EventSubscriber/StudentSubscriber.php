<?php
namespace App\EventSubscriber;

use App\Entity\Student;
use App\Service\StudentTrieService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class StudentSubscriber implements EventSubscriber
{
    private StudentTrieService $studentTrieService;

    public function __construct(StudentTrieService $studentTrieService)
    {
        $this->studentTrieService = $studentTrieService;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Student) {
            $this->studentTrieService->addStudentToTrie($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Student) {
            $this->studentTrieService->updateStudentInTrie($entity);
        }
    }
}

<?php

namespace App\Factory;

use App\Entity\Session;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class SessionFactory
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(User $user, ?DateTimeInterface $activatedAt = null, ?DateTimeInterface $appointmentAt = null)
    {
        $session = new Session($user, $activatedAt, $appointmentAt);
        $this->entityManager->persist($session);

        return $session;
    }
}

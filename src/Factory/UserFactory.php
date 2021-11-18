<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Entity\User;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserFactory
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(string $email, Customer $customer, ?DateTimeInterface $registeredAt = null)
    {
        if (!$registeredAt) {
            $registeredAt = new DateTime();
        }
        $user = new User($email, $customer, $registeredAt);
        $this->entityManager->persist($user);

        return $user;
    }
}

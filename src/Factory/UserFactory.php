<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserFactory
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(string $email, Customer $customer, ?DateTimeInterface $registeredAt = null)
    {
        $user = new User($email, $customer, $registeredAt);
        $this->entityManager->persist($user);

        return $user;
    }
}

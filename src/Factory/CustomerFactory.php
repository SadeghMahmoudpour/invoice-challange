<?php

namespace App\Factory;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;

class CustomerFactory
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(string $name)
    {
        $customer = new Customer($name);
        $this->entityManager->persist($customer);

        return $customer;
    }
}

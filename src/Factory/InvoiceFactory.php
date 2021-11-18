<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Entity\Invoice;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceFactory
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(Customer $customer, DateTimeInterface $start, DateTimeInterface $end)
    {
        $amount = 0;
        $invoice = new Invoice($customer, $start, $end, $amount);
        $this->entityManager->persist($invoice);

        return $invoice;
    }
}

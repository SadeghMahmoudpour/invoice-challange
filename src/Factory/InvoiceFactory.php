<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Services\InvoceCalculator\InvoiceCalculatorInterface;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private InvoiceCalculatorInterface $invoiceCalculator
    ) {
    }

    public function create(Customer $customer, DateTimeInterface $start, DateTimeInterface $end)
    {
        $invoice = new Invoice($customer, $start, $end);
        $this->invoiceCalculator->calculate($invoice);
        $this->entityManager->persist($invoice);

        return $invoice;
    }
}

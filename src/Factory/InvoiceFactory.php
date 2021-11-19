<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Services\InvocePriceCalculator\InvoicePriceCalculatorInterface;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private InvoicePriceCalculatorInterface $invoicePriceCalculator
    ) {
    }

    public function create(Customer $customer, DateTimeInterface $start, DateTimeInterface $end)
    {
        $amount = $this->invoicePriceCalculator->calculate($customer, $start, $end);
        $invoice = new Invoice($customer, $start, $end, $amount);
        $this->entityManager->persist($invoice);

        return $invoice;
    }
}

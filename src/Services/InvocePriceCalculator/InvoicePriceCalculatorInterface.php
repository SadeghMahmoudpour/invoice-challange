<?php

namespace App\Services\InvocePriceCalculator;

use App\Entity\Customer;
use DateTimeInterface;

interface InvoicePriceCalculatorInterface
{
    public function calculate(Customer $customer, DateTimeInterface $start, DateTimeInterface $end): float;
}

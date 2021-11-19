<?php

namespace App\Services\InvoceCalculator;

use App\Entity\Invoice;

interface InvoiceCalculatorInterface
{
    public function calculate(Invoice $invoice);
}

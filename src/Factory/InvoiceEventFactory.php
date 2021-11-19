<?php

namespace App\Factory;

use App\Entity\Event;
use App\Entity\Invoice;
use App\Entity\InvoiceEvent;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceEventFactory
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(Invoice $invoice, User $user, string $event, ?string $lastEvent = null)
    {
        $invoiceEvent = new InvoiceEvent($invoice, $user, $event, $lastEvent);
        $this->entityManager->persist($invoiceEvent);

        return $invoiceEvent;
    }
}

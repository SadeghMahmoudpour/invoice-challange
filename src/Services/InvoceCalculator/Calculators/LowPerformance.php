<?php

namespace App\Services\InvoceCalculator\Calculators;

use App\Entity\Invoice;
use App\Entity\InvoiceEvent;
use App\Entity\User;
use App\Factory\InvoiceEventFactory;
use App\Services\InvoceCalculator\InvoiceCalculatorInterface;
use DateTimeInterface;

class LowPerformance implements InvoiceCalculatorInterface
{
    public function __construct(private InvoiceEventFactory $invoiceEventFactory)
    {
    }

    public function calculate(Invoice $invoice)
    {
        foreach ($invoice->getCustomer()->getUsers() as $user) {
            $this->calculateUserEvents($invoice, $user);
        }
    }

    private function calculateUserEvents(Invoice $invoice, User $user)
    {
        $expensiveEvent = $this->getExpensiveEvent($user, $invoice->getEndsAt(), $invoice->getStartsAt());
        if (!$expensiveEvent) {
            return;
        }
        $paidExpensiveEvent = $this->getExpensiveEvent($user, $invoice->getStartsAt());
        if ($paidExpensiveEvent && $this->getEventPrice($paidExpensiveEvent) >= $this->getEventPrice($expensiveEvent)) {
            return;
        }

        $this->invoiceEventFactory->create($invoice, $user, $expensiveEvent, $paidExpensiveEvent);
    }

    private function getExpensiveEvent(User $user, DateTimeInterface $before, DateTimeInterface $after = null): ?string
    {
        foreach (InvoiceEvent::EVENT_SORTED_PRICE as $event => $price) {
            if (
                ($event === InvoiceEvent::EVENT_APPOINTMENT && $this->getLastAppointment($user, $before, $after))
                || ($event === InvoiceEvent::EVENT_ACTIVATION && $this->getLastActivation($user, $before, $after))
                || ($event === InvoiceEvent::EVENT_REGISTER && $user->getRegisteredAt() < $before && (!$after || $user->getRegisteredAt() >= $after))
            ) {
                return $event;
            }
        }

        return null;
    }

    private function getLastAppointment(User $user, DateTimeInterface $before, DateTimeInterface $after = null): ?DateTimeInterface
    {
        $result = null;
        foreach ($user->getSessions() as $session) {
            $event = $session->getAppointmentAt();
            if (
                $event
                && $event < $before
                && (!$after || $event >= $after)
                && (!$result || $event > $result)
            ) {
                $result = $event;
            }
        }

        return $result;
    }

    private function getLastActivation(User $user, DateTimeInterface $before, DateTimeInterface $after = null): ?DateTimeInterface
    {
        $result = null;
        foreach ($user->getSessions() as $session) {
            $event = $session->getActivatedAt();
            if (
                $event
                && $event < $before
                && (!$after || $event >= $after)
                && (!$result || $event > $result)
            ) {
                $result = $event;
            }
        }

        return $result;
    }

    private function getEventPrice(string $event)
    {
        return InvoiceEvent::EVENT_SORTED_PRICE[$event];
    }
}

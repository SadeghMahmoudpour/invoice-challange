<?php

namespace App\Services\InvocePriceCalculator\Calculators;

use App\Entity\Customer;
use App\Entity\User;
use App\Services\InvocePriceCalculator\InvoicePriceCalculatorInterface;
use DateTimeInterface;

class LowPerformance implements InvoicePriceCalculatorInterface
{
    const EVENT_REGISTER = 'register';
    const EVENT_ACTIVATION = 'activation';
    const EVENT_APPOINTMENT = 'appointment';
    const EVENT_PRICE = [
        self::EVENT_REGISTER => 0.49,
        self::EVENT_ACTIVATION => 0.99,
        self::EVENT_APPOINTMENT => 3.99,
    ];

    public function calculate(Customer $customer, DateTimeInterface $start, DateTimeInterface $end): float
    {
        $result = 0.0;
        foreach ($customer->getUsers() as $user) {
            $result += $this->calculateUserPrice($user, $start, $end);
        }

        return $result;
    }

    private function calculateUserPrice(User $user, DateTimeInterface $start, DateTimeInterface $end): float
    {
        $expensiveEvent = $this->getExpensiveEvent($user, $end, $start);
        if (!$expensiveEvent) {
            return 0.0;
        }
        $paidExpensiveEvent = $this->getExpensiveEvent($user, $start);
        if (!$paidExpensiveEvent) {
            return self::EVENT_PRICE[$expensiveEvent];
        }

        return max(0, self::EVENT_PRICE[$expensiveEvent] - self::EVENT_PRICE[$paidExpensiveEvent]);
    }

    private function getExpensiveEvent(User $user, DateTimeInterface $before, DateTimeInterface $after = null): ?string
    {
        $expensiveEvent = null;
        if ($this->getLastAppointment($user, $before, $after)) {
            $expensiveEvent = self::EVENT_APPOINTMENT;
        } elseif ($this->getLastActivation($user, $before, $after)) {
            $expensiveEvent = self::EVENT_ACTIVATION;
        } elseif ($user->getRegisteredAt() < $before && (!$after || $user->getRegisteredAt() >= $after)) {
            $expensiveEvent = self::EVENT_REGISTER;
        }

        return $expensiveEvent;
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
}

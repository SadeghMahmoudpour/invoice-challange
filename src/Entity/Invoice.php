<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["invoice:get:basic"])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private Customer $customer;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $startsAt;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $endsAt;

    #[ORM\Column(type: 'float')]
    private float $totalPrice;

    public function __construct(
        Customer $customer,
        DateTimeInterface $startsAt,
        DateTimeInterface $endsAt,
        float $totalPrice
    ) {
        $this->customer = $customer;
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
        $this->totalPrice = $totalPrice;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getStartsAt(): DateTimeInterface
    {
        return $this->startsAt;
    }

    public function getEndsAt(): DateTimeInterface
    {
        return $this->endsAt;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }
}

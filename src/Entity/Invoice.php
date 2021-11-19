<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceEvent::class)]
    private Collection $invoiceEvents;

    public function __construct(
        Customer $customer,
        DateTimeInterface $startsAt,
        DateTimeInterface $endsAt,
        float $totalPrice = 0.0
    ) {
        $this->customer = $customer;
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
        $this->totalPrice = $totalPrice;
        $this->invoiceEvents = new ArrayCollection;
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

    /**
     * @return InvoiceEvent[]|Collection
     */
    public function getInvoiceEvents(): Collection
    {
        return $this->invoiceEvents;
    }

    public function addInvoiceEvent(InvoiceEvent $invoiceEvent)
    {
        if ($this->invoiceEvents->contains($invoiceEvent)) {
            return $this;
        }
        $this->invoiceEvents->add($invoiceEvent);
        $this->totalPrice += $invoiceEvent->getPrice();
        $invoiceEvent->setInvoice($this);

        return $this;
    }
}

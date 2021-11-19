<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "`user`")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Customer $customer;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $registeredAt;

    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'user')]
    private Collection $sessions;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceEvent::class)]
    private Collection $invoiceEvents;

    public function __construct(string $email, Customer $customer, DateTimeInterface $registeredAt)
    {
        $this->sessions = new ArrayCollection;
        $this->invoiceEvents = new ArrayCollection;
        $this->email = $email;
        $this->registeredAt = $registeredAt;
        $this->setCustomer($customer);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        $customer->addUser($this);

        return $this;
    }

    /**
     * @return Session[]|Collection
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session)
    {
        if ($this->sessions->contains($session)) {
            return $this;
        }
        $this->sessions->add($session);
        $session->setUser($this);

        return $this;
    }

    public function getRegisteredAt(): DateTimeInterface
    {
        return $this->registeredAt;
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
        $invoiceEvent->setUser($this);

        return $this;
    }
}

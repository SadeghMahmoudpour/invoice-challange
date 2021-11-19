<?php

namespace App\Entity;

use App\Repository\InvoiceEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceEventRepository::class)]
class InvoiceEvent
{
    const EVENT_REGISTER = 'register';
    const EVENT_ACTIVATION = 'activation';
    const EVENT_APPOINTMENT = 'appointment';
    const EVENT_SORTED_PRICE = [
        self::EVENT_APPOINTMENT => 3.99,
        self::EVENT_ACTIVATION => 0.99,
        self::EVENT_REGISTER => 0.49,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'invoiceEvents')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Invoice $invoice;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'invoiceEvents')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'string', length: 255)]
    private string $event;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lastEvent;

    #[ORM\Column(type: 'float')]
    private float $price;

    public function __construct(Invoice $invoice, User $user, string $event, ?string $lastEvent = null)
    {
        $this->event = $event;
        $this->lastEvent = $lastEvent;
        $this->calculatePrice();
        $this->setInvoice($invoice);
        $this->setUser($user);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $invoice->addInvoiceEvent($this);

        return $this;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $user->addInvoiceEvent($this);

        return $this;
    }

    private function calculatePrice()
    {
        if (!$this->lastEvent) {
            $this->price = self::EVENT_SORTED_PRICE[$this->event];

            return $this;
        }
        $this->price = self::EVENT_SORTED_PRICE[$this->event] - self::EVENT_SORTED_PRICE[$this->lastEvent];
        if ($this->price <= 0) {
            throw new \Exception('Invalid InvoiceEvent');
        }

        return $this;
    }
}

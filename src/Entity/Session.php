<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $activatedAt;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $appointmentAt;

    public function __construct(User $user, ?DateTimeInterface $activatedAt = null, ?DateTimeInterface $appointmentAt = null)
    {
        $this->setUser($user);
        $this->activatedAt = $activatedAt;
        $this->appointmentAt = $appointmentAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivatedAt(): ?DateTimeInterface
    {
        return $this->activatedAt;
    }

    public function getAppointmentAt(): ?DateTimeInterface
    {
        return $this->appointmentAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $user->addSession($this);

        return $this;
    }
}

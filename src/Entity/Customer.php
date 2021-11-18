<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'customer')]
    private Collection $users;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->users = new ArrayCollection;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user)
    {
        if ($this->users->contains($user)) {
            return $this;
        }
        $this->users->add($user);
        $user->setCustomer($this);

        return $this;
    }
}

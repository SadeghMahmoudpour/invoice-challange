<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Exists;
use App\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceModel
{
    #[Groups(["invoice:post"])]
    #[Assert\NotBlank(groups: ["invoice:post"])]
    #[Assert\Date(groups: ["invoice:post"])]
    public string $start;

    #[Groups(["invoice:post"])]
    #[Assert\NotBlank(groups: ["invoice:post"])]
    #[Assert\Date(groups: ["invoice:post"])]
    public string $end;

    #[Groups(["invoice:post"])]
    #[Assert\NotBlank(groups: ["invoice:post"])]
    #[Assert\Type(type: 'integer',groups: ["invoice:post"])]
    #[Exists(entityClass: Customer::class, groups: ["invoice:post"])]
    public int $customerId;
}

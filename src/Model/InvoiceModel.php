<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;
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
}

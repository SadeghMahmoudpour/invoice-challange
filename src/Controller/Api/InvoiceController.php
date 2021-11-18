<?php

namespace App\Controller\Api;

use App\Configuration\RequestModel;
use App\Configuration\RestView;
use App\Model\InvoiceModel;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @OA\Tag(name="Invoice")
 */
#[Route("/invoices", name: "invoice_")]
class InvoiceController extends AbstractController
{
    /**
     * @OA\Post(
     *     @OA\RequestBody(
     *         @Model(type=InvoiceModel::class, groups={"invoice:post"})
     *     )
     * )
     */
    #[Route("", name: "create", methods: ["POST"])]
    #[RequestModel("invoiceModel", groups:["invoice:post"])]
    #[RestView(serializerGroups:[
        "invoice:get",
    ])]
    public function create(InvoiceModel $invoiceModel, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->json($validationErrors, 422);
        }

        return $invoiceModel;
    }
}

<?php

namespace App\Controller\Api;

use App\Configuration\RequestModel;
use App\Configuration\RestView;
use App\Entity\Invoice;
use App\Factory\InvoiceFactory;
use App\Model\InvoiceModel;
use App\Repository\CustomerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
    public function __construct(
        private CustomerRepository $customerRepository,
        private InvoiceFactory $invoiceFactory,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @OA\Post(
     *     @OA\RequestBody(
     *         @Model(type=InvoiceModel::class, groups={"invoice:post"})
     *     )
     * )
     */
    #[Route("", name: "create", methods: ["POST"])]
    #[RequestModel("invoiceModel", groups:["invoice:post"])]
    #[RestView(serializerGroups:["invoice:get:basic"])]
    public function create(InvoiceModel $invoiceModel, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->json($validationErrors, 422);
        }
        $customer = $this->customerRepository->find($invoiceModel->customerId);
        $invoice = $this->invoiceFactory->create(
            $customer,
            new DateTime($invoiceModel->start),
            new DateTime($invoiceModel->end)
        );

        $this->entityManager->flush();

        return $invoice;
    }

    /**
     * @OA\Get()
     */
    #[Route("/{id}", methods:["GET"], name:"detail")]
    #[RestView(serializerGroups:["invoice:get", "invoiceEvent:get", "user:get"])]
    public function detail(Invoice $invoice)
    {
        return $invoice;
    }
}

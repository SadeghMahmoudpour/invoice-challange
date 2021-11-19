<?php

namespace App\Tests\Api\Invoice;

use App\DataFixtures\AppFixtures;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\InvoiceEvent;
use App\Tests\Api\AbstractApiTestCase;

class CreateTest extends AbstractApiTestCase
{
    protected function getFixtures()
    {
        return [
            AppFixtures::class,
        ];
    }

    public function testCreateSuccessfully()
    {
        /** @var Customer $customer */
        $customer = $this->getRepository(Customer::class)->findOneBy([], ['id' => 'asc']);

        $invoiceCount = $this->entityCount(Invoice::class);
        $invoiceEventCount = $this->entityCount(InvoiceEvent::class);

        $this->apiClient()->request('POST', 'invoices', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'start' => '2021-01-01',
            'end' => '2021-02-01',
            'customerId' => $customer->getId(),
        ]));

        self::assertResponseIsSuccessful();
        self::assertEquals($invoiceCount + 1, $this->entityCount(Invoice::class));
        /** @var Invoice $invoice */
        $invoice = $this->lastEntity(Invoice::class);
        self::assertEquals('2021-01-01', $invoice->getStartsAt()->format('Y-m-d'));
        self::assertEquals('2021-02-01', $invoice->getEndsAt()->format('Y-m-d'));
        self::assertEquals($customer->getId(), $invoice->getCustomer()->getId());
        self::assertEquals(4.99, $invoice->getTotalPrice());

        self::assertEquals($invoiceEventCount + 3, $this->entityCount(InvoiceEvent::class));
    }
}

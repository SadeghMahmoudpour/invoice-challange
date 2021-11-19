<?php

namespace App\Tests\Api;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractApiTestCase extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->loadFixtures();
    }

    protected function getFixtures()
    {
        return [];
    }

    protected function loadFixtures()
    {
        $fixtures = $this->getFixtures();
        if (!$fixtures) {
            return;
        }
        $ormExecutor = new ORMExecutor($this->getEntityManager(), new ORMPurger($this->getEntityManager()));
        $fixtures = array_map([$this, 'getService'], $fixtures);
        $ormExecutor->execute($fixtures);
    }

    protected function tearDown(): void
    {
        $this->client = null;
        parent::tearDown();
    }

    protected function apiClient(): KernelBrowser
    {
        $this->client->setServerParameter('HTTP_HOST', 'api.invoice.local');
        $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');

        return $this->client;
    }

    public function getEntityManager(): EntityManager
    {
        return self::$container->get(EntityManagerInterface::class);
    }

    public function getRepository($entityClass): EntityRepository
    {
        return $this->getEntityManager()->getRepository($entityClass);
    }

    public function getService($serviceId): object
    {
        return self::$container->get($serviceId);
    }

    public function entityCount($entityClass): int
    {
        $count = $this->getEntityManager()
            ->getRepository($entityClass)
            ->createQueryBuilder('entity')
            ->select('count(entity.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return intval($count);
    }

    public function lastEntity($entityClass, array $criteria = [])
    {
        return $this->getEntityManager()
            ->getRepository($entityClass)
            ->findOneBy($criteria, ['id' => 'desc'])
            ;
    }
}
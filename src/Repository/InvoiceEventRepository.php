<?php

namespace App\Repository;

use App\Entity\InvoiceEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvoiceEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceEvent[]    findAll()
 * @method InvoiceEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceEvent::class);
    }

    // /**
    //  * @return InvoiceEvent[] Returns an array of InvoiceEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InvoiceEvent
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

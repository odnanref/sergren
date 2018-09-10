<?php

namespace App\Repository;

use App\Entity\ProductReportView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductReportView|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductReportView|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductReportView[]    findAll()
 * @method ProductReportView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductReportViewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductReportView::class);
    }
    
    function save($report) : self 
    {
        $this->_em->persist($report);
        $this->_em->flush();
        return $this;
    }

    /**
     * removes a report
     * 
     * @param ProductReportView $report
     * @return self
     */
    function removeOld($report) : self
    {
        $this->_em->remove($report);
        $this->_em->flush();
        
        return $this;
    }
//    /**
//     * @return ProductReportView[] Returns an array of ProductReportView objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductReportView
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

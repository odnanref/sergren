<?php

namespace App\Repository;

use App\Entity\CategoryReportView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategoryReportView|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryReportView|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryReportView[]    findAll()
 * @method CategoryReportView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryReportViewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategoryReportView::class);
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
     * @param CategoryReportView $report
     * @return self
     */
    function removeOld($report) : self
    {
        $this->_em->remove($report);
        $this->_em->flush();
        
        return $this;
    }
//    /**
//     * @return CategoryReportView[] Returns an array of CategoryReportView objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryReportView
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

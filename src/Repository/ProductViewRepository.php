<?php

namespace App\Repository;

use App\Entity\ProductView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductView|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductView|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductView[]    findAll()
 * @method ProductView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductViewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductView::class);
    }

    function getAll($year, $month, $product)
    {
        $fromTime = new \DateTime($year . '-' . $month . '-01');
        $toTime = new \DateTime($fromTime->format('Y-m-d') . ' first day of next month');
        
        return $this->createQueryBuilder('pv')
        ->andWhere('pv.datecreated >= :start')
        ->andWhere("pv.datecreated < :end ")
        ->andWhere("pv.product = :product")
        ->setParameter("product", $product->getId())
        ->setParameter('start', $fromTime)
        ->setParameter("end", $toTime)
        ->orderBy('pv.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }
//    /**
//     * @return ProductView[] Returns an array of ProductView objects
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
    public function findOneBySomeField($value): ?ProductView
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

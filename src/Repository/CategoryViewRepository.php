<?php

namespace App\Repository;

use App\Entity\CategoryView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategoryView|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryView|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryView[]    findAll()
 * @method CategoryView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryViewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategoryView::class);
    }

    function getAll($year, $month, $category)
    {
        $fromTime = new \DateTime($year . '-' . $month . '-01');
        $toTime = new \DateTime($fromTime->format('Y-m-d') . ' first day of next month');
        
        return $this->createQueryBuilder('pv')
        ->andWhere('pv.datecreated >= :start')
        ->andWhere("pv.datecreated < :end ")
        ->andWhere("pv.Category = :category")
        ->setParameter("category", $category->getId())
        ->setParameter('start', $fromTime)
        ->setParameter("end", $toTime)
        ->orderBy('pv.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }
//    /**
//     * @return CategoryView[] Returns an array of CategoryView objects
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
    public function findOneBySomeField($value): ?CategoryView
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

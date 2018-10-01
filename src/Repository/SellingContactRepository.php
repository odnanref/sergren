<?php

namespace App\Repository;

use App\Entity\SellingContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SellingContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method SellingContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method SellingContact[]    findAll()
 * @method SellingContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SellingContactRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SellingContact::class);
    }

//    /**
//     * @return SellingContact[] Returns an array of SellingContact objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SellingContact
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

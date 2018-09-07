<?php

namespace App\Repository;

use App\Entity\DefaultAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DefaultAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method DefaultAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method DefaultAttribute[]    findAll()
 * @method DefaultAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefaultAttributeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DefaultAttribute::class);
    }

//    /**
//     * @return DefaultAttribute[] Returns an array of DefaultAttribute objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DefaultAttribute
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

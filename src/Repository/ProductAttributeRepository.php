<?php

namespace App\Repository;

use App\Entity\ProductAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAttribute[]    findAll()
 * @method ProductAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAttributeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductAttribute::class);
    }
    
    public function save(ProductAttribute $pa): self {
        $this->_em->persist($pa);
        $this->_em->flush();
        return $this;
    }

//    /**
//     * @return ProductAttribute[] Returns an array of ProductAttribute objects
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
    public function findOneBySomeField($value): ?ProductAttribute
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

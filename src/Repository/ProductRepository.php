<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }
    
    function getWindowProducts() {
        return $this->createQueryBuilder('p')
        ->innerJoin('p.productAttributes', 'pA')
        ->andWhere('pA.name = :val')
        ->setParameter('val', "destaque")
        ->andWhere('pA.value = :val1')
        ->setParameter('val1', "1")
        ->orderBy('p.datecreated', 'DESC')
        ->setMaxResults(4)
        ->getQuery()
        ->getResult()
        ;
    }

    function getActiveProductsByCategory($id) {
        $q = $this->createQueryBuilder("p")
        ->leftJoin("p.productAttributes", "pA")
        ->leftJoin("p.medias", "medias")
        ->leftJoin("p.categories", 'categories')
        ->andWhere("p.state = 1 ")
        ->andWhere("categories.id = :id")
        ->setParameter("id", $id)
        ->getQuery();
        
        return $q->getResult();
    }
    
    function getActive(int $id) {
        $q = $this->createQueryBuilder("p")
        ->leftJoin("p.productAttributes", "pA")
        ->leftJoin("p.medias", "medias")
        ->leftJoin("p.categories", 'categories')
        ->andWhere("p.state = 1 ")
        ->andWhere("p.id = :id")
        ->setParameter("id", $id)
        ->getQuery();
        
        return $q->getResult();
        
    }
    
    function getActiveByUrl(string $url) {
        $q = $this->createQueryBuilder("p")
        ->leftJoin("p.productAttributes", "pA")
        ->leftJoin("p.medias", "medias")
        ->leftJoin("p.categories", 'categories')
        ->andWhere("p.state = 1 ")
        ->andWhere("p.url = :url")
        ->setParameter("url", $url)
        ->getQuery();
        
        return $q->getResult();
    }
    
    /**
     * Returns true if column count > 0
     *  
     * @param string $url
     * @return boolean
     */
    function existsUrl(string $url, $id = 0 ) {
        $q = $this->createQueryBuilder("p")
        ->andWhere("p.url = :url")
        ->setParameter("url", $url)
        ;
        
        if ($id > 0 ) {
            $q->andWhere("p.id != :id ");
            $q->setParameter("id", $id);
        }
        $q = $q->getQuery();
        
        return ( count($q->getResult()) > 0 );
    }
    
    function search($product, $active = true) {
        $q = $this->createQueryBuilder("p")
        ->leftJoin("p.productAttributes", "pA")
        ->leftJoin("p.medias", "medias")
        ->leftJoin("p.categories", 'categories');
        
        if ($active === true) {
            $q->andWhere("p.state = 1 ");
        }
        
        $q = $q->andWhere("MATCH_AGAINST( p.name, p.description, p.keywords , :search)  > 0.0")
        ->setParameter("search", $product)
        ->getQuery();
        
        return $q->getResult();
    }
//    /**
//     * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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

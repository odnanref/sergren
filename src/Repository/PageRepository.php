<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    function getActiveByUrl(string $url) {
        $q = $this->createQueryBuilder("p")
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
//    /**
//     * @return Page[] Returns an array of Page objects
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
    public function findOneBySomeField($value): ?Page
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

<?php

namespace App\Repository;

use App\Entity\Auto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Auto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auto[]    findAll()
 * @method Auto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auto::class);
    }

    // /**
    //  * @return Auto[] Returns an array of Auto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Auto
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    //queryBuilder
    public function findAllGreaterThanPrice($price):array
    {
        return $this->createQueryBuilder('a')
                    ->andWhere('a.prix > :p') 
                    ->setParameter('p',$price)
                    ->orderBy('a.prix','DESC')
                    ->getQuery()
                    ->getResult();
    }
    //Dql
    public function findAllGreaterThanPrice2($price):array
    {
        $em = $this->getEntityManager();
        $q = $em->createQuery(
            'SELECT a
            FROM App\Entity\Auto a
            WHERE a.prix > :p'
        )->setParameter('p',$price);

        return $q->getResult();
        
    }
    //Sql
    public function findAllGreaterThanPrice3($price):array
    {
        $db = $this->getEntityManager()->getConnection();
        $req = '
            SELECT * FROM Auto a
            WHERE a.prix > :p
        ';
        $result = $db->prepare($req);
        $result->execute(['p'=>$price]);
        return $result->fetchAllAssociative();
    }
}

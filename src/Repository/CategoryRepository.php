<?php

namespace App\Repository;

use App\Entity\Auto;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
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
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getAutosByCategory($categoryId, $search=null){
        $em = $this->getEntityManager();
        if($search){
            $query = $em->createQuery(
                'SELECT a, c
                FROM App\Entity\Auto a
                INNER JOIN a.category c 
                WHERE (c.id =:id_cat AND (a.marque LIKE :search OR a.modele LIKE :search))'
            )->setParameters(['id_cat'=> $categoryId, 'search'=>'%'.$search.'%']);
        }else{
            $query = $em->createQuery(
                'SELECT a, c
                FROM App\Entity\Auto a
                INNER JOIN a.category c 
                WHERE c.id =:id_cat'
            )->setParameters(['id_cat'=> $categoryId]);

        }
        return $query->execute();
    }
}

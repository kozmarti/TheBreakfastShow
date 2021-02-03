<?php

namespace App\Repository;

use App\Entity\ListIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListIngredient[]    findAll()
 * @method ListIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListIngredient::class);
    }

    // /**
    //  * @return ListIngredient[] Returns an array of ListIngredient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListIngredient
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

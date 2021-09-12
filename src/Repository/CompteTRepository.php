<?php

namespace App\Repository;

use App\Entity\CompteT;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompteT|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteT|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteT[]    findAll()
 * @method CompteT[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompteT::class);
    }

    // /**
    //  * @return CompteT[] Returns an array of CompteT objects
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
    public function findOneBySomeField($value): ?CompteT
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

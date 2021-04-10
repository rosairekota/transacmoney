<?php

namespace App\Repository;

use App\Entity\Expediteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Expediteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expediteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expediteur[]    findAll()
 * @method Expediteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpediteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expediteur::class);
    }
    // public function findByEmail(int $id)
    // {
    //     return $this->getQueryBuilder()
    //         ->andWhere('exp.user_retrait= :id')
    //         ->setParameter('id', $id)
    //         ->orderBy('exp.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    // private function getQueryBuilder(){
    //     return $this->createQueryBuilder('exp');
    // }
    // /**
    //  * @return Expediteur[] Returns an array of Expediteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Expediteur
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

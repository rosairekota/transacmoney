<?php

namespace App\Repository;

use App\Entity\Search;
use App\Entity\Retrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Retrait|null find($id, $lockMode = null, $lockVersion = null)
 * @method Retrait|null findOneBy(array $criteria, array $orderBy = null)
 * @method Retrait[]    findAll()
 * @method Retrait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetraitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Retrait::class);
    }

    // /**
    //  * @return Retrait[] Returns an array of Retrait objects
    //  */
    /**/
    public function findSecretCode(Search $search)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.code_retrait = :val')
            ->setParameter('val', $search->code_secret)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        
    }
    

    /*
    public function findOneBySomeField($value): ?Retrait
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

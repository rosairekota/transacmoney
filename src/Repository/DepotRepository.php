<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Depot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Depot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depot[]    findAll()
 * @method Depot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em)
    {
        parent::__construct($registry, Depot::class);
        $this->em=$em;
    }

    // /**
    //  * @return Depot[] Returns an array of Depot objects
    //  */
    
    public function findByEmail(int $id)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.user_depot= :val')
            ->setParameter('val', $id)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function selectByIdSql($datas)
    {
            $con=$this->em->getConnection();
             $sql="SELECT * FROM depot d
             WHERE d.user_depot_id=:id";
        
            $stmt=$con->prepare($sql);
            $stmt->execute($datas);
           return $stmt->fetchAllAssociative();
        
    }

    /*
    public function findOneBySomeField($value): ?Depot
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

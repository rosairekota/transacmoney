<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Compte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compte[]    findAll()
 * @method Compte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em)
    {
        parent::__construct($registry, Compte::class);
        $this->em=$em;
    }
    
    public function findByUser(User $user){
        return $this->createQueryBuilder('c')
            ->andWhere('c.user_compte = :val')
            ->setParameter('val', $user->getId())
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }

    public function findSumAccountBySql($datas)
    {
        
            $con=$this->em->getConnection();
             $sql="SELECT SUM(montant_debit) as debit,SUM(montant_credit) as credit FROM compte d
             WHERE user_compte_id=:id";
        
        $stmt=$con->prepare($sql);
        $stmt->execute($datas);
       return $stmt->fetchAll();
        
    }
    // /**
    //  * @return Compte[] Returns an array of Compte objects
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
    public function findOneBySomeField($value): ?Compte
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

<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Search;
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
        return $this->getQueryBuilder()
            ->andWhere('d.user_depot= :val')
            ->setParameter('val', $id)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findSecretCodeByOrm(Search $search)
    {      
        return $this->getQueryBuilder()
             ->andWhere('d.codeDepot= :code_depot')
            ->setParameter('code_depot',$search->code_secret)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findSecretCodeBySql($datas)
    {
        
            $con=$this->em->getConnection();
             $sql="SELECT * FROM depot d
             WHERE d.code_depot=:code_depot";
        
            $stmt=$con->prepare($sql);
            $stmt->execute($datas);
           return $stmt->fetchAllAssociative();
        
    }
    public function findSumDepotsBySql($table,$attribute)
    {
        
            $con=$this->em->getConnection();
             $sql="SELECT SUM({$attribute}) as somme FROM {$table} d
             ";
        
            $stmt=$con->executeQuery($sql);
           return $stmt->fetchAll();
        
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
  
    private function getQueryBuilder(){
        return $this->createQueryBuilder('d');
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

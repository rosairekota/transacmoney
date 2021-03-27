<?php

namespace App\Repository;

use App\Entity\Search;
use App\Entity\Retrait;
use Doctrine\ORM\EntityManagerInterface;
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
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em)
    {
        parent::__construct($registry, Retrait::class);
        $this->em=$em;
    }
    
    public function findByEmail(int $id)
    {
        return $this->getQueryBuilder()
            ->andWhere('r.user_retrait= :id')
            ->setParameter('id', $id)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Retrait[] Returns an array of Retrait objects
    //  */
    /**/
    public function findSecretCode(Search $search)
    {
        return $this->getQueryBuilder()
            ->select('user','r')
            ->join('r.user_retrait','user')
            ->select('depot','r')
            ->join('r.depot','depot')
            ->andWhere('r.code_retrait = :val')
            ->setParameter('val', $search->code_secret)
            ->getQuery()
            ->getResult();
        
    }
    public function findSecretCodeSql($search)
    {
            $con=$this->em->getConnection();
             $sql="SELECT * FROM retrait d
             WHERE d.code_retrait=:code_retrait";
        
            $stmt=$con->prepare($sql);
            $stmt->execute($search);
           return $stmt->fetchAllAssociative();
        
    }

    public function insertBySql($datas=[])
    {
            $con=$this->em->getConnection();
            
             $sql="INSERT INTO retrait(depot_id,user_retrait_id,montant_retire,date_retrait,beneficiaire_piece_type,beneficiaire_piece_numero,libelle,code_retrait) 
             VALUES(:depot_id, :user_retrait_id, :montant_retire, NOW(), :beneficiaire_piece_type, :beneficiaire_piece_numero, :libelle, :code_retrait)";
            $con->prepare($sql);
            $result= $con->executeQuery($sql,$datas);
            return $result;
        
    }
    private function getQueryBuilder(){
        return $this->createQueryBuilder('r');
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

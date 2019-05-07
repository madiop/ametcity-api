<?php

namespace App\Repository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Entity\Professionnels;

/**
 * @method Professionnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Professionnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Professionnel[]    findAll()
 * @method Professionnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionnelsRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Professionnels::class);
    }

    public function search($term, $order = 'asc', $limit = 20, $offset = 0)
    {
        $qb = $this
            ->createQueryBuilder('p')
            // ->orderBy('a.title', $order)
        ;
        
        // if ($term) {
        //     $qb
        //         ->andWhere('a.title LIKE :term')
        //         ->setParameter('term', '%'.$term.'%')
        //     ;
        // }
        
        return $this->paginate($qb, $limit, $offset);
    }

    public function update(Professionnels $professionnel, Professionnels $newProfessionnel){

        if(!is_null($newProfessionnel->getTjm())){
            $professionnel->setTjm($newProfessionnel->getTjm());
        }
        if(!is_null($newProfessionnel->getStatus())){
            $professionnel->setStatus($newProfessionnel->getStatus());
        }
        if(!is_null($newProfessionnel->getDescription())){
            $professionnel->setDescription($newProfessionnel->getDescription());
        }
        if(!is_null($newProfessionnel->getExperience())){
            $professionnel->setExperience($newProfessionnel->getExperience());
        }
        return $professionnel;
    }

    // /**
    //  * @return Professionnel[] Returns an array of Professionnel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Professionnel
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Entity\Professionels;

/**
 * @method Professionel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Professionel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Professionel[]    findAll()
 * @method Professionel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionelsRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Professionels::class);
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

    public function update(Professionels $professionel, Professionels $newProfessionel){

        if(!is_null($newProfessionel->getTjm())){
            $professionel->setTjm($newProfessionel->getTjm());
        }
        if(!is_null($newProfessionel->getStatus())){
            $professionel->setStatus($newProfessionel->getStatus());
        }
        if(!is_null($newProfessionel->getDescription())){
            $professionel->setDescription($newProfessionel->getDescription());
        }
        if(!is_null($newProfessionel->getExperience())){
            $professionel->setExperience($newProfessionel->getExperience());
        }
        return $professionel;
    }

    // /**
    //  * @return Professionel[] Returns an array of Professionel objects
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
    public function findOneBySomeField($value): ?Professionel
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

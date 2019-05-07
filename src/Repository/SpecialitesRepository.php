<?php

namespace App\Repository;

use App\Entity\Specialites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Specialites|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialites|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialites[]    findAll()
 * @method Specialites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialitesRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Specialites::class);
    }

    public function search($term, $order = 'asc', $limit = 20, $offset = 0)
    {
        $qb = $this
            ->createQueryBuilder('s')
            ->orderBy('s.libelle', $order)
        ;
        
        if ($term) {
            $qb
                ->andWhere('s.libelle LIKE :term')
                ->setParameter('term', '%'.$term.'%')
            ;
        }
        
        return $this->paginate($qb, $limit, $offset);
    }

    public function findOrCreate(Specialites $specialite): ?Specialites
    {
        $spec = $this->createQueryBuilder('s')
            ->andWhere('s.libelle = :val')
            ->setParameter('val', $specialite->getLibelle())
            ->getQuery()
            ->getOneOrNullResult()
        ;
        return is_null($spec) ? $specialite : $spec;
    }

    // /**
    //  * @return Specialites[] Returns an array of Specialites objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Specialites
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

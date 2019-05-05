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
class SpecialitesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Specialites::class);
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

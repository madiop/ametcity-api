<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Produits::class);
    }
    
    public function search($term, $order = 'asc', $limit = 20, $offset = 0)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->orderBy('p.nom', $order)
        ;
        
        if ($term) {
            $qb
                ->andWhere('p.nom LIKE :term')
                ->setParameter('term', '%'.$term.'%')
            ;
        }
        
        return $this->paginate($qb, $limit, $offset);
    }

    public function update(Produits $produit, Produits $newProduit){

        if(!is_null($newProduit->getNom())){
            $produit->setNom($newProduit->getNom());
        }
        if(!is_null($newProduit->getDescription())){
            $produit->setDescription($newProduit->getDescription());
        }
        if(!is_null($newProduit->getCategorie())){
            $produit->setCategorie($newProduit->getCategorie());
        }
        return $produit;
    }

    // /**
    //  * @return Produits[] Returns an array of Produits objects
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
    public function findOneBySomeField($value): ?Produits
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

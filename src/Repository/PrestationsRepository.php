<?php

namespace App\Repository;

use App\Entity\Produits;
use App\Entity\Professionnels;
use App\Entity\Entreprises;
use App\Entity\Prestations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Prestations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prestations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prestations[]    findAll()
 * @method Prestations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrestationsRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Prestations::class);
    }
    
    public function search($term, $order = 'asc', $limit = 20, $offset = 0)
    {
        $qb = $this
            ->createQueryBuilder('p')
        ;

        if ($term) {
            $qb
                ->innerJoin('p.produit', 'prod')
                ->innerJoin('prod.categorie', 'c')
                ->andWhere('c.nom = :term')
                ->setParameter('term', $term)
            ;
        }
        
        return $this->paginate($qb, $limit, $offset);
    }

    public function update(Prestations $prestation, Prestations $newPrestation){

        if(!is_null($newPrestation->getPrixUnitaire())){
            $prestation->setPrixUnitaire($newPrestation->getPrixUnitaire());
        }
        if(!is_null($newPrestation->getSpecifications())){
            $prestation->setSpecifications($newPrestation->getSpecifications());
        }
        if(!is_null($newPrestation->getTva())){
            $prestation->setTva($newPrestation->getTva());
        }
        if(!is_null($newPrestation->getStatus())){
            $prestation->setStatus($newPrestation->getStatus());
        }
        
        return $prestation;
    }

    public function sanitize(Prestations $prestation){
        
        $produitId = $prestation->getProduit()->getId();
        $professionnel = $prestation->getProfessionnel();
        $entreprise = $prestation->getEntreprises();

        $prodRepo = $this->getEntityManager()->getRepository(Produits::class);
        $profRepo = $this->getEntityManager()->getRepository(Professionnels::class);
        $entRepo = $this->getEntityManager()->getRepository(Entreprises::class);

        $prestation->setProduit($prodRepo->find($produitId));

        if(!is_null($professionnel)){
            $prestation->setProfessionnel($profRepo->find($professionnel->getId()));
        }
        if(!is_null($entreprise)){
            $prestation->setEntreprises($entRepo->find($entreprise->getId()));
        }

        return $prestation;
    }

    // /**
    //  * @return Prestations[] Returns an array of Prestations objects
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
    public function findOneBySomeField($value): ?Prestations
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

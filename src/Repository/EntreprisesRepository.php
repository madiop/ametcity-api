<?php

namespace App\Repository;

use App\Entity\Entreprises;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Entreprises|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprises|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprises[]    findAll()
 * @method Entreprises[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntreprisesRepository  extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Entreprises::class);
    }

    public function search($term, $order = 'asc', $limit = 20, $offset = 0)
    {
        $qb = $this
            ->createQueryBuilder('e')
            ->orderBy('e.raisonSociale', $order)
        ;
        
        if ($term) {
            $qb
                ->andWhere('e.raisonSociale LIKE :term')
                ->setParameter('term', '%'.$term.'%')
            ;
        }
        
        return $this->paginate($qb, $limit, $offset);
    }

    public function update(Entreprises $entreprise, Entreprises $newEntreprise){

        if(!is_null($newEntreprise->getRaisonSociale())){
            $entreprise->setRaisonSociale($newEntreprise->getRaisonSociale());
        }
        if(!is_null($newEntreprise->getFax())){
            $entreprise->setFax($newEntreprise->getFax());
        }
        if(!is_null($newEntreprise->getSiren())){
            $entreprise->setSiren($newEntreprise->getSiren());
        }
        if(!is_null($newEntreprise->getRcsVille())){
            $entreprise->setRcsVille($newEntreprise->getRcsVille());
        }
        if(!is_null($newEntreprise->getCodeNaf())){
            $entreprise->setCodeNaf($newEntreprise->getCodeNaf());
        }
        if(!is_null($newEntreprise->getNumeroTva())){
            $entreprise->setNumeroTva($newEntreprise->getNumeroTva());
        }
        return $entreprise;
    }

    // /**
    //  * @return Entreprises[] Returns an array of Entreprises objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Entreprises
    {
        return $this->createQueryBuilder('c')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

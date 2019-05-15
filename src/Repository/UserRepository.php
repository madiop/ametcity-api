<?php

namespace App\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityRepository;

use App\Entity\User;
class UserRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByUsername($term, $order = 'asc', $limit = 20, $offset = 0)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.username LIKE :username OR u.email = :email')
            ->setParameter('username', '%'.$term.'%')
            ->setParameter('email', '%'.$term.'%');
            // ->getQuery()
            // ->getOneOrNullResult();
        
        return $this->paginate($qb, $limit, $offset);
    }

    public function findOneByToken($token): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.confirmationToken = :val')
            ->setParameter('val', $token)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function update(User $user, User $newUser){

        if(!is_null($newUser->getPrenom())){
            $user->setPrenom($newUser->getPrenom());
        }
        if(!is_null($newUser->getNom())){
            $user->setNom($newUser->getNom());
        }
        if(!is_null($newUser->getTelephone())){
            $user->setTelephone($newUser->getTelephone());
        }
        if(!is_null($newUser->getAdresse())){
            $user->setAdresse($newUser->getAdresse());
        }
        if(!is_null($newUser->getVille())){
            $user->setVille($newUser->getVille());
        }
        if(!is_null($newUser->getPays())){
            $user->setPays($newUser->getPays());
        }
        if(!is_null($newUser->getCodePostale())){
            $user->setCodePostale($newUser->getCodePostale());
        }
        return $user;
    }
}
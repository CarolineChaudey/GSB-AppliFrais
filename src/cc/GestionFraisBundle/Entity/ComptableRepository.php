<?php

namespace cc\GestionFraisBundle\Entity;

/**
 * ComptableRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComptableRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function trouverComptable($login, $mdp){
        
        $comptable = $this->createQueryBuilder('c')
                ->where('c.login = :login')
                ->setParameter('login', $login)
                ->andWhere('c.mdp = :mdp')
                ->setParameter('mdp', $mdp)
                ->getQuery()
                ->getOneOrNullResult();
        return $comptable;
                
    }
    
}
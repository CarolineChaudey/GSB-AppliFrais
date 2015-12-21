<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace cc\GestionFraisBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ComptableRepository
 *
 * @author caroline
 */
class ComptableRepository extends EntityRepository{
    
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

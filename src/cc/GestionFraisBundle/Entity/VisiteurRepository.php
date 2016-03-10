<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace cc\GestionFraisBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of VisiteurRepository
 *
 * @author caroline
 */
class VisiteurRepository extends EntityRepository {
    
    public function trouverVisiteur($login, $mdp){
        $visiteur = $this->createQueryBuilder('v')
                ->where('v.login = :login')
                ->setParameter('login', $login)
                ->andWhere('v.mdp = :mdp')
                ->setParameter('mdp', $mdp)
                ->getQuery()
                ->getOneOrNullResult();
        return $visiteur;
    }
    
}

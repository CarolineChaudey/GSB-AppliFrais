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
class FichefraisRepository extends EntityRepository{
    
     public function trouverFichesValides(){
         
         $fiches = $this->createQueryBuilder('f')
                ->where('f.idetat = :etat')
                ->setParameter('etat', 'VA')
                ->getQuery()
                ->getResult();
        return $fiches;
     }
    
}


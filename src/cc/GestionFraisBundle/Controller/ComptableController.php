<?php

namespace cc\GestionFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ComptableController extends Controller
{
    public function indexAction(Request $request){
        return $this->render('ccGestionFraisBundle:Comptable:v_sommaire.html.twig', array("user" => $request->getSession()->get("user")));
    }
    
    public function validerFichesAction(Request $request){
        $user = $request->getSession()->get('user');
        $modele = $this->container->get('modele');
        
        $repo = $this->getDoctrine()->getManager()->getRepository('ccGestionFraisBundle:Visiteur');
        $lesVisiteurs = $repo->findAll();
        
        $form = $this->createFormBuilder()
                
                ->add('visiteur', 'entity', 
                        array('class'=>'ccGestionFraisBundle:Visiteur')
                    )
                ->add('mois', 'text')
                ->add('Rechercher', 'submit')
                ->add('Annuler', 'reset')
                ->getForm();
        
        $form->handleRequest($request);
        if($form->isValid()){
            $visiteur = $form["visiteur"]->getData();
            $mois = $form["mois"]->getData();
            $mois1 = substr($mois, 0, 3);
            $mois2 = substr($mois, 5, 2);
            $mois = $mois1.$mois2;
            
            $fiche = $modele->getLesInfosFicheFrais($visiteur->getId(),$mois);
            $ffs = $modele->getLesFraisForfait($visiteur->getId(), $mois);
            $fhfs = $modele->getLesFraisHorsForfait($visiteur->getId(), $mois);
            
            
            return $this->render('ccGestionFraisBundle:Comptable:v_validation_fiche.html.twig', array(
                "user" => $user,
                'fiche' => $fiche,
                'ffs' => $ffs,
                'fhfs' => $fhfs,
                'visiteur' => $visiteur,
                'mois' => $mois
            ));         
        }
        
        return $this->render('ccGestionFraisBundle:Comptable:v_validation_fiches.html.twig', array(
            "user" => $user,
            "lesVisiteurs" => $lesVisiteurs,
            "form" => $form->createView()
        ));
    }
}


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
        
        $lesVisiteurs = $modele->getListeVisiteurs();
        
        $form = $this->createFormBuilder()
                ->add('visiteur', 'choice')
                ->add('mois', 'text')
                ->add('Rechercher', 'submit')
                ->add('Annuler', 'reset')
                ->getForm();
        
        return $this->render('ccGestionFraisBundle:Comptable:v_validation_fiches.html.twig', array(
            "user" => $user,
            "lesVisiteurs" => $lesVisiteurs,
            "form" => $form->createView()
        ));
    }
}


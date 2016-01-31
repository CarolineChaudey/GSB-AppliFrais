<?php

namespace cc\GestionFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Render;
use Symfony\Component\HttpFoundation\Request;
use cc\GestionFraisBundle\Entity\Fichefrais;
use cc\GestionFraisBundle\Entity\Etat;

class VisiteurController extends Controller
{
    public function indexAction(Request $request){
        return $this->render('ccGestionFraisBundle:Visiteur:v_sommaire.html.twig', array("user" => $request->getSession()->get("user")));
    }
    
    public function saisieAction(Request $request){
        
        
        
        $modele = $this->container->get('modele');
        // à développer
        $mois = sprintf("%04d%02d", date("Y"), date("m"));
        $nouvMois = $modele->estPremierFraisMois($request->getSession()->get("user")->getId(), $mois);
        
        if($nouvMois === true){
            $modele->creeNouvellesLignesFrais($request->getSession()->get("user")->getId(),$mois);
        }
        
        $mois = $modele->dernierMoisSaisi($request->getSession()->get("user")->getId());
        $fiche = $modele->getLesInfosFicheFrais($request->getSession()->get("user")->getId(),$mois);
        
        return $this->render('ccGestionFraisBundle:Visiteur:v_saisie.html.twig', array("user" => $request->getSession()->get("user"),
                                                                                        "fiche" => $fiche,
                                                                                        "mois" => $mois));
    }
}

<?php

namespace cc\GestionFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Render;
use Symfony\Component\HttpFoundation\Request;

class VisiteurController extends Controller
{
    public function indexAction(Request $request){
        return $this->render('ccGestionFraisBundle:Visiteur:v_sommaire.html.twig', array("user" => $request->getSession()->get("user")));
    }
    
    public function saisieAction(Request $request){
        $modele = $this->container->get('modele');
        // à développer
        return $this->render('ccGestionFraisBundle:Visiteur:v_saisie.html.twig', array("user" => $request->getSession()->get("user")));
    }
}

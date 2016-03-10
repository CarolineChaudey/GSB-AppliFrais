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
        
        $mois = sprintf("%04d%02d", date("Y"), date("m"));
        $nouvMois = $modele->estPremierFraisMois($request->getSession()->get("user")->getId(), $mois);
        
        if($nouvMois === true){
            $modele->creeNouvellesLignesFrais($request->getSession()->get("user")->getId(),$mois);
        }
        
        $fraisforfaits = $modele->getLesFraisForfait($request->getSession()->get("user")->getId(), $mois);
        
        $formForfait = $this->createFormBuilder()
                ->add('etape', 'integer', array('required' => true, 'label' => 'Forfait étape', 'data' => $fraisforfaits[1]['quantite']))
                ->add('km', 'integer', array('required' => true, 'label' => 'Frais kilométriques', 'data' => $fraisforfaits[1]['quantite']))
                ->add('hotel', 'integer', array('required' => true, 'label' => 'Nuité hôtel', 'data' => $fraisforfaits[2]['quantite']))
                ->add('resto', 'integer', array('required' => true, 'label' => 'Repas restaurant', 'data' => $fraisforfaits[3]['quantite']))
                ->add('Valider', 'submit')
                ->add('Annuler', 'reset')
                ->getForm();
        
        if($formForfait->handleRequest($request)->isValid()){
            $result = array(
                $fraisforfaits[0]['idfrais'] => $formForfait['etape']->getData(),
                $fraisforfaits[1]['idfrais'] => $formForfait['km']->getData(),
                $fraisforfaits[2]['idfrais'] => $formForfait['hotel']->getData(),
                $fraisforfaits[3]['idfrais'] => $formForfait['resto']->getData()
            );
            $modele->majFraisForfait($request->getSession()->get("user")->getId(), $mois, $result);
        }
        
        $mois = $modele->dernierMoisSaisi($request->getSession()->get("user")->getId());
        $fiche = $modele->getLesInfosFicheFrais($request->getSession()->get("user")->getId(),$mois);
        
        return $this->render('ccGestionFraisBundle:Visiteur:v_saisie.html.twig', array("user" => $request->getSession()->get("user"),
                                                                                        "fiche" => $fiche,
                                                                                        "mois" => $mois,
                                                                                        "formForfait" => $formForfait->createView()));
    }
}

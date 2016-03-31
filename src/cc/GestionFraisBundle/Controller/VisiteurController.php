<?php

namespace cc\GestionFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VisiteurController extends Controller
{
    public function indexAction(Request $request){
        return $this->render('ccGestionFraisBundle:Visiteur:v_sommaire.html.twig', array("user" => $request->getSession()->get("user")));
    }
    
    public function saisieAction(Request $request){
        
        $modele = $this->container->get('modele');
        $user = $request->getSession()->get("user");
        
        $mois = sprintf("%04d%02d", date("Y"), date("m"));
        $nouvMois = $modele->estPremierFraisMois($user["id"], $mois);
        
        if($nouvMois === true){
            $modele->creeNouvellesLignesFrais($user["id"],$mois);
        }
        
        $fraisforfaits = $modele->getLesFraisForfait($user["id"], $mois);
       
        
        $fhf = $modele->getLesFraisHorsForfait($user["id"], $mois);
        
        $mois = $modele->dernierMoisSaisi($user["id"]);
        $fiche = $modele->getLesInfosFicheFrais($user["id"], $mois);
        
        return $this->render('ccGestionFraisBundle:Visiteur:v_saisie.html.twig', array("user" => $user,
                                                                                        "fiche" => $fiche,
                                                                                        "mois" => $mois,
                                                                                        "fhfactuels" => $fhf,
                                                                                        "fraisforfait" => $fraisforfaits
                                                                                        ));
    }
    
    public function traiterFraisAction(Request $request){
        $mois = sprintf("%04d%02d", date("Y"), date("m"));
        $modele = $this->container->get('modele');
        $user = $request->getSession()->get("user");
        
        $result = array(
                'ETP' => $_POST['nbEtapes'],
                'KM' => $_POST['nbKms'],
                'NUI' => $_POST['nbNuits'],
                'REP' => $_POST['nbRepas']
            );
        $modele->majFraisForfait($user["id"], $mois, $result);
       
        return $this->redirectToRoute('saisieFiche');
    }
    
    public function supprimerFraisAction(Request $request){
        $id = $_POST['id'];
        $modele = $this->container->get('modele');
        $modele->supprimerFraisHorsForfait($id);
        
        return $this->redirectToRoute('saisieFiche');
    }
    
    public function creerFraisAction(Request $request){
        $modele = $this->container->get('modele');
        $user = $request->getSession()->get("user");
        $mois = sprintf("%04d%02d", date("Y"), date("m"));
        $modele->creerNouveauFraisHorsForfait($user["id"],
                $mois,$_POST['libelle'],$_POST['date'],$_POST['montant']);
        
        return $this->redirectToRoute('saisieFiche');
    }
    
    public function consulterFraisAction(Request $request){
        $modele = $this->container->get('modele');
        $user = $request->getSession()->get("user");
        $lesMoisDispo = $modele->getLesMoisDisponibles($user["id"]);
        $visiteur = $user;
        
        return $this->render('ccGestionFraisBundle:Visiteur:v_consultation_fiches.html.twig', array(
            "user" => $visiteur,
            "listeMois" => $lesMoisDispo
        ));
    }
    
    public function consulterUnFraisAction(Request $request, $mois){
        $user = $request->getSession()->get("user");
        $modele = $this->container->get('modele');
        $ffs = $modele->getLesFraisForfait($user["id"], $mois);
        $fhfs = $modele->getLesFraisHorsForfait($user["id"], $mois);
        $fiche = $modele->getLesInfosFicheFrais($user["id"],$mois);
                
        return $this->render('ccGestionFraisBundle:Visiteur:v_consultation_fiche.html.twig',array(
            'mois' => $mois,
            'user' => $user,
            'ffs' => $ffs,
            'fhfs' => $fhfs,
            'fiche' => $fiche
        ));
    }
}

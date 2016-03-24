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
        
        $mois = sprintf("%04d%02d", date("Y"), date("m"));
        $nouvMois = $modele->estPremierFraisMois($request->getSession()->get("user")->getId(), $mois);
        
        if($nouvMois === true){
            $modele->creeNouvellesLignesFrais($request->getSession()->get("user")->getId(),$mois);
        }
        
        $fraisforfaits = $modele->getLesFraisForfait($request->getSession()->get("user")->getId(), $mois);
       
        
        $fhf = $modele->getLesFraisHorsForfait($request->getSession()->get('user')->getId(), $mois);
        
        $mois = $modele->dernierMoisSaisi($request->getSession()->get("user")->getId());
        $fiche = $modele->getLesInfosFicheFrais($request->getSession()->get("user")->getId(),$mois);
        
        return $this->render('ccGestionFraisBundle:Visiteur:v_saisie.html.twig', array("user" => $request->getSession()->get("user"),
                                                                                        "fiche" => $fiche,
                                                                                        "mois" => $mois,
                                                                                        "fhfactuels" => $fhf,
                                                                                        "fraisforfait" => $fraisforfaits
                                                                                        ));
    }
    
    public function traiterFraisAction(Request $request){
        $mois = sprintf("%04d%02d", date("Y"), date("m"));
        $modele = $this->container->get('modele');
        
        $result = array(
                'ETP' => $_POST['nbEtapes'],
                'KM' => $_POST['nbKms'],
                'NUI' => $_POST['nbNuits'],
                'REP' => $_POST['nbRepas']
            );
        $modele->majFraisForfait($request->getSession()->get("user")->getId(), $mois, $result);
       
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
        $mois = sprintf("%04d%02d", date("Y"), date("m"));
        $modele->creerNouveauFraisHorsForfait($request->getSession()->get("user")->getId(),
                $mois,$_POST['libelle'],$_POST['date'],$_POST['montant']);
        
        return $this->redirectToRoute('saisieFiche');
    }
    
    public function consulterFraisAction(Request $request){
        $modele = $this->container->get('modele');
        $lesMoisDispo = $modele->getLesMoisDisponibles($request->getSession()->get("user")->getId());
        $visiteur = $request->getSession()->get("user");
        
        return $this->render('ccGestionFraisBundle:Visiteur:v_consultation_fiches.html.twig', array(
            "user" => $visiteur,
            "listeMois" => $lesMoisDispo
        ));
    }
    
    public function consulterUnFraisAction(Request $request, $mois){
        $user = $request->getSession()->get("user");
        $modele = $this->container->get('modele');
        $ffs = $modele->getLesFraisForfait($user->getId(), $mois);
        $fhfs = $modele->getLesFraisHorsForfait($user->getId(), $mois);
        $fiche = $modele->getLesInfosFicheFrais($request->getSession()->get("user")->getId(),$mois);
        
        return $this->render('ccGestionFraisBundle:Visiteur:v_consultation_fiche.html.twig',array(
            'mois' => $mois,
            'user' => $user,
            'ffs' => $ffs,
            'fhfs' => $fhfs,
            'fiche' => $fiche
        ));
    }
}

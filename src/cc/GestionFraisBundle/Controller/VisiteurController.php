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
        
        //$mois = $modele->dernierMoisSaisi($user["id"]);
        $fiche = $modele->getLesInfosFicheFrais($user["id"], $mois);
        
        // formulaire des frais forfaitisés
        $formFF = $this->get('form.factory')->createNamedBuilder("ff")
                ->add('etapes', 'integer', array('data' => $fraisforfaits['0']['quantite'],
                                                    'attr' => array('min' => 0)))
                ->add('kms', 'integer', array('data' => $fraisforfaits['1']['quantite'],
                                                    'attr' => array('min' => 0)))
                ->add('nuits', 'integer', array('data' => $fraisforfaits['2']['quantite'],
                                                    'attr' => array('min' => 0)))
                ->add('repas', 'integer', array('data' => $fraisforfaits['3']['quantite'],
                                                    'attr' => array('min' => 0)))
                ->add('valider', 'submit', array('label' => 'Valider'))
                ->add('annuler', 'reset', array('label' => 'Annuler'))
                ->getForm();
        
        // formulaire des frais hors forfait
        $formFHF = $this->get('form.factory')->createNamedBuilder("fhf")
                ->add('date', 'date', array('label' => 'Date', 'data' => new \DateTime()))
                ->add('libelle', 'text', array('label' => 'Libelle'))
                ->add('montant', 'number', array('label' => 'Montant', 'scale' => 2))
                ->add('valider', 'submit', array('label' => 'Créer'))
                ->getForm();
        
        if('POST' === $request->getMethod()){
            if($request->request->has('ff')){
                $formFF->handleRequest($request);
                $result = array(
                            'ETP' => $formFF['etapes']->getData(),
                            'KM' => $formFF['kms']->getData(),
                            'NUI' => $formFF['nuits']->getData(),
                            'REP' => $formFF['repas']->getData()
                );
                
                $modele->majFraisForfait($user["id"], $mois, $result);
                return $this->redirectToRoute('saisieFiche');
            }
            elseif($request->request->has('fhf')){
                $formFHF->handleRequest($request);
                $date = date_format($formFHF['date']->getData(), 'Y-m-d');
                $modele->creerNouveauFraisHorsForfait($user["id"],$mois,$formFHF['libelle']->getData(),
                        $date,$formFHF['montant']->getData());
        
                return $this->redirectToRoute('saisieFiche');
            }
        }
        
        return $this->render('ccGestionFraisBundle:Visiteur:v_saisie.html.twig', array("user" => $user,
                                                                                        "fiche" => $fiche,
                                                                                        "mois" => $mois,
                                                                                        "formFF" => $formFF->createView(),
                                                                                        "formFHF" => $formFHF->createView(),
                                                                                        "fhfactuels" => $fhf
                                                                                        ));
    }
    
    public function supprimerFraisAction(Request $request, $id){
        //$id = $_POST['id'];
        $modele = $this->container->get('modele');
        $modele->annulerFraisHorsForfait($id);
        
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

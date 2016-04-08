<?php

namespace cc\GestionFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use cc\GestionFraisBundle\Entity\Fichefrais;
use cc\GestionFraisBundle\Entity\FichefraisRepository;

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
            $mois1 = substr($mois, 0, 4);
            $mois2 = substr($mois, 5, 2);
            $mois = $mois1.$mois2;
            
            $fiche = $modele->getLesInfosFicheFrais($visiteur->getId(),$mois);
            
            return $this->redirectToRoute('consulterFiche', array('idVis' => $fiche['idVis'],
                                                                    'mois' => $fiche['mois']) );
        }
        
        return $this->render('ccGestionFraisBundle:Comptable:v_validation_fiches.html.twig', array(
            "user" => $user,
            "lesVisiteurs" => $lesVisiteurs,
            "form" => $form->createView()
        ));
    }
    
    public function consulterFicheAction(Request $request, $idVis, $mois){
        $modele = $this->container->get('modele');
        $repo = $this->getDoctrine()->getRepository('ccGestionFraisBundle:Visiteur');
        
        $fiche = $modele->getLesInfosFicheFrais($idVis,$mois);
        $user = $request->getSession()->get('user');
        $visiteur = $repo->find($idVis);
        $ffs = $modele->getLesFraisForfait($idVis, $mois);
        $fhfs = $modele->getLesFraisHorsForfait($idVis, $mois);
        
        $form = $this->createFormBuilder()
                ->add('nbJustificatifs', 'integer', array('label' => 'Nombre de justificatifs',
                                                            'data' => $fiche['nbJustificatifs']))
                ->add('montantValide', 'number', array('label' => 'Montant validé',
                                                            'precision' => 2,
                                                            'data' => $fiche['montantValide']))
                ->add('Valider','submit')
                ->getForm();
        
        $form->handleRequest($request);
        if($form->isValid()){
            $nbJustificatifs = $form['nbJustificatifs']->getData();
            $montantValide = $form['montantValide']->getData();
            
            $modele->majNbJustificatifs($idVis, $mois, $nbJustificatifs);
            $modele->majMontantValide($idVis, $mois, $montantValide);
        }
        
        return $this->render('ccGestionFraisBundle:Comptable:v_validation_fiche.html.twig', array(
                "user" => $user,
                'fiche' => $fiche,
                'ffs' => $ffs,
                'fhfs' => $fhfs,
                'visiteur' => $visiteur,
                'mois' => $mois,
                'form' => $form->createView()
            ));      
    }
    
    public function supprimerFraisAction(Request $request, $id){
            $modele = $this->container->get('modele');
            
            $leFrais = $modele->getFraisHorsForfait($id);
            $idVis = $leFrais['idVisiteur'];
            $mois = $leFrais['mois'];
            $modele->supprimerFraisHorsForfait($id);
            
            return $this->redirectToRoute('consulterFiche', array('idVis' => $idVis, 'mois' => $mois));
    }
    
    public function validerFicheAction(Request $request, $idVis, $mois){
        $modele = $this->container->get('modele');
        $modele->majEtatFicheFrais($idVis,$mois,'VA');
        
        return $this->redirectToRoute('validerFiches');
    }
    
    public function suivreFichesAction(Request $request){
        $modele = $this->container->get('modele');
        $user = $request->getSession()->get('user');
        
        $lesFiches = $this->getDoctrine()->getRepository('ccGestionFraisBundle:Fichefrais')->trouverFichesValides();
        
        return $this->render('ccGestionFraisBundle:Comptable:v_fiches_valides.html.twig', array('user' => $user,
                                                                                                'lesFiches' => $lesFiches));
    }
    
    public function vueFicheRemboursementAction(Request $request, $idVis, $mois){
        $modele = $this->container->get('modele');
        $repo = $this->getDoctrine()->getRepository('ccGestionFraisBundle:Visiteur');
        
        $fiche = $modele->getLesInfosFicheFrais($idVis,$mois);
        $user = $request->getSession()->get('user');
        $visiteur = $repo->find($idVis);
        $ffs = $modele->getLesFraisForfait($idVis, $mois);
        $fhfs = $modele->getLesFraisHorsForfait($idVis, $mois);
        
        return $this->render('ccGestionFraisBundle:Comptable:v_fiche_remboursement.html.twig', array(
                "user" => $user,
                'fiche' => $fiche,
                'ffs' => $ffs,
                'fhfs' => $fhfs,
                'visiteur' => $visiteur,
                'mois' => $mois
            ));    
    }
    
    public function rembourserFicheAction(Request $request, $idVis, $mois){
        $modele = $this->container->get('modele');
        $modele->majEtatFicheFrais($idVis,$mois,'RB');
        
        return $this->redirectToRoute('suivre');
    }
    
}


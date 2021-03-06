<?php

namespace cc\GestionFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use cc\GestionFraisBundle\BaseDeDonnees\Services\PdoGsb;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        
        $request->getSession()->set('user', null);
        
        $form = $this->createFormBuilder()
                ->add('login', 'text')
                ->add('mdp', 'password')
                ->add('profil', 'choice', array('choices'=>array('visiteur'=>'Visiteur',
                                                            'comptable'=>'Comptable')))
                ->add('valider', 'submit')
                ->add('annuler', 'reset')
                ->getForm();
        
        $form->handleRequest($request);
        if($form->isValid()){
            $modele = $this->container->get('modele');
            $login = $form['login']->getData();
            $mdp = $form['mdp']->getData();
            $profil = $form['profil']->getData();
            $user = null;
            
            if($profil == "visiteur"){
                $visiteur = $modele->getInfosVisiteur($login, $mdp);
                $user = $visiteur;
            }
            else if($profil == "comptable"){
                $comptable = $modele->getInfosComptable($login, $mdp);
                $user = $comptable;
            }
            
            if($user !== FALSE){
                $request->getSession()->set('user', $user);
                if($profil === 'visiteur'){
                    return $this->redirectToRoute('visiteur');
                }
                else if($profil === 'comptable'){
                    return $this->redirectToRoute('comptable');
                }
            }
            else{
                //return $this->render('ccGestionFraisBundle:Default:erreurCo.html.twig');
                $this->addFlash('fail', 'Identifiants incorrects : avez-vous sélectionné le bon profil ?');
            }
        }
        
        return $this->render('ccGestionFraisBundle:Default:v_connexion.html.twig', array('form'=>$form->createView()));
    }
 
    public function deconnexionAction(Request $request){
        $request->getSession()->clear();
        return $this->redirectToRoute('connexion');
    }
    
}

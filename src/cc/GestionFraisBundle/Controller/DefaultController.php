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
            $login = $form['login']->getData();
            $mdp = $form['mdp']->getData();
            $profil = $form['profil']->getData();
            
            $visiteur = null;
            if($profil == "visiteur"){
                $repo = $this->getDoctrine()->getManager()->getRepository('ccGestionFraisBundle:Visiteur');
                $visiteur = $repo->trouverVisiteur($login, $mdp);
                
            }
            else if($profil == "comptable"){
                return new Response("comptable");
            }
            
            return $this->render('ccGestionFraisBundle:Default:test.html.twig', array('visiteur' => $visiteur));
        }
        
        return $this->render('ccGestionFraisBundle:Default:v_connexion.html.twig', array('form'=>$form->createView()));
    }
}

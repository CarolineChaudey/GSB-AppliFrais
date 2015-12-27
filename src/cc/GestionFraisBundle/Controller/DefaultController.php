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
            
            $user = null;
            if($profil == "visiteur"){
                $repo = $this->getDoctrine()->getManager()->getRepository('ccGestionFraisBundle:Visiteur');
                $visiteur = $repo->trouverVisiteur($login, $mdp);
                $user = $visiteur;
            }
            else if($profil == "comptable"){
                $repo = $this->getDoctrine()->getManager()->getRepository('ccGestionFraisBundle:Comptable');
                $comptable = $repo->trouverComptable($login, $mdp);
                $user = $comptable;    
            }
            
            if($user !== null){
                $request->getSession()->set('user', $user);
                $request->getSession()->set('profil', $profil);
                if($request->getSession()->get('profil') === "visiteur"){
                    //return $this->render('ccGestionFraisBundle:Default:test.html.twig', array('user' => $user));
                    $this->redirectToRoute('visiteur');
                }
                else if($request->getSession()->get('profil') === "comptable"){
                    return $this->render('ccGestionFraisBundle:Default:test.html.twig', array('user' => $user));
                }
            }
            else{
                //return $this->render('ccGestionFraisBundle:Default:erreurCo.html.twig');
                $this->addFlash('fail', 'Identifiants incorrects : avez-vous sélectionné le bon profil ?');
            }
        }
        
        return $this->render('ccGestionFraisBundle:Default:v_connexion.html.twig', array('form'=>$form->createView()));
    }
}

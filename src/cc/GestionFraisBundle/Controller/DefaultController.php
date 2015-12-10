<?php

namespace cc\GestionFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            $login = $form->getData('login');
            $mdp = $form->getData('mdp');
            $profil = $form->getData('profil');
            $infosCo = array(
                'login'=>$login,
                'mdp'=>$mdp,
                'profil'=>$profil
            );
            
            return $this->render('ccGestionFraisBundle:Default:test.html.twig', array('login'=>$login,
                                                                                        'mdp'=>$mdp,
                                                                                        'profil'=>$profil));
        }
        
        return $this->render('ccGestionFraisBundle:Default:v_connexion.html.twig', array('form'=>$form->createView()));
    }
}

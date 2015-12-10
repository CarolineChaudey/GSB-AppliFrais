<?php

namespace cc\GestionFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Render;

class VisiteurController extends Controller
{
    public function indexAction(){
        return $this->render('ccGestionFraisBundle:Default:v_generale.html.twig');
    }
}

<?php

namespace Ft\HomeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('FtHomeBundle:Default:index.html.twig', array('typekit_js' => $this->container->getParameter('typekit_js')));
    }

}

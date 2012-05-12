<?php

namespace Ft\HomeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {	 
        return $this->render('FtHomeBundle:Default:index.html.twig', array('typekit_js' => $this->container->getParameter('typekit_js'), 'home_tab_style' => ' class=selected','faq_tab_style' => ''));
        //return $this->render('FtHomeBundle:Default:index.html.twig');
    }

    public function faqAction()
    {	 
        return $this->render('FtHomeBundle:Default:faq.html.twig', array('typekit_js' => $this->container->getParameter('typekit_js'), 'home_tab_style' => '','faq_tab_style' => ' class=selected'));
        //return $this->render('FtHomeBundle:Default:index.html.twig');
    }

}

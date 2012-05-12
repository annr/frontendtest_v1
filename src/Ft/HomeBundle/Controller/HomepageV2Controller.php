<?php

namespace Ft\HomeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomepageV2Controller extends Controller
{
    
    public function indexAction()
    {	
        return $this->render('FtHomeBundle:HomepageV2:index.html.twig', array(
		    'stylesheet' => 'style_v2.css',
			'typekit_js' => $this->container->getParameter('typekit_js'),
		));
    }

}

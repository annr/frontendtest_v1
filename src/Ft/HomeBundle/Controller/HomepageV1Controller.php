<?php

namespace Ft\HomeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomepageV1Controller extends Controller
{
    
    public function indexAction()
    {	
        return $this->render('FtHomeBundle:HomepageV1:index.html.twig', array(
		    'stylesheet' => 'style_v1.css',
		));
    }

}

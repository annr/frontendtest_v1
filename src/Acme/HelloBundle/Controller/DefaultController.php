<?php

namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('support@frontendtest.com')
            ->setTo('ann@bhnc.com')
            ->setBody($this->renderView('AcmeHelloBundle:Default:index.html.twig', array('name' => $name)));

        $this->get('mailer')->send($message);

        return $this->render('AcmeHelloBundle:Default:index.html.twig', array('name' => $name));
    }
}

<?php

namespace Ft\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ft\HomeBundle\Entity\FtRequest;

class ReportController extends Controller
{
    
    public function indexAction($id)
    {
	    $em = $this->getDoctrine()->getEntityManager();

        $ft_request = $em->getRepository('FtHomeBundle:FtRequest')->find($id);

        if (!$ft_request) {
            throw $this->createNotFoundException('Unable to find FtRequest entity.');
        }

		$url = $ft_request->getUrl();
		$summary = $ft_request->getReportSummary();
		
		$results = $em->getRepository('FtCoreBundle:TestResult')->findBy(
		    array('ft_request_id' => $id),
		    array('weight' => 'DESC')
		);
		
/*	
	  $ft_request = new FtRequest();
      $ft_request->setEmail($_POST['email']);
      $ft_request->setUrl($_POST['url']);
      $ft_request->setCreated(new \DateTime('now'));
      $ft_request->setType('FREE');

      $wantsMoreHelp_bool = null;
	  if (isset($_POST['wantsMoreHelp']) && $_POST['wantsMoreHelp'] == 'on') { $wantsMoreHelp_bool = 1; }
      $ft_request->setMoretestsReq($wantsMoreHelp_bool);

      $em = $this->getDoctrine()->getEntityManager();
      $em->persist($ft_request);
      //$em->persist($product);

      $em->flush();
*/
/*
      $message = \Swift_Message::newInstance();

	  $cid = $message->embed(\Swift_Image::fromPath('http://localhost/frontendtest/web/img/logo_sm.png'));
		
      $message->setSubject('FrontendTest Report')
       ->setFrom('support@frontendtest.com')
       ->setTo('support@frontendtest.com')
  	   ->setContentType('text/html')
       ->setBody($this->renderView('FtCoreBundle:Report:email.html.twig', array('cid' => $cid, 'date' => date("D M j G:i:s T Y"), 'url' => $url, 'results' => $results)));

      $this->get('mailer')->send($message);
*/
      $response = $this->render('FtCoreBundle:Report:email.html.twig', array('cid' => 'cid:1334090611.4f849b738d290@localhost', 'date' => date("D M j G:i:s T Y"), 'url' => $url, 'results' => $results, 'summary' => $summary));
      $response->headers->set('Content-Type', 'text/html');
      return $response;
    }
}

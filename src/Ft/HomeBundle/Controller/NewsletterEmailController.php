<?php
namespace Ft\HomeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ft\HomeBundle\Entity\NewsletterEmail;
use Symfony\Component\HttpFoundation\Request;

class NewsletterEmailController extends Controller
{
    public function indexAction()
    {

      $newsletter_email = new NewsletterEmail();
      $newsletter_email->setEmail($_POST['email']);
      $newsletter_email->setCreated(new \DateTime('now'));

      $em = $this->getDoctrine()->getEntityManager();;
      $em->persist($newsletter_email);
      $em->flush();

      //email support@ft with details.
      $message = \Swift_Message::newInstance()
        ->setSubject('Newsletter Signup Request')
        ->setFrom('support@frontendtest.com')
        ->setTo('support@frontendtest.com')
        ->setBody($this->renderView('FtHomeBundle:Default:newsletter_signup.html.twig', array('email' => $_POST['email'])));

      $this->get('mailer')->send($message);

      $response = $this->render('FtHomeBundle:Default:newsletter_signup.txt.twig');
      $response->headers->set('Content-Type', 'text/plain');
      return $response;

    }


}

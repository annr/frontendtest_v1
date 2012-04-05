<?php
namespace Ft\HomeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ft\HomeBundle\Entity\FtRequest;
use Ft\HomeBundle\Entity\FreeProduct;
use Symfony\Component\HttpFoundation\Request;

class FtRequestController extends Controller
{
    public function indexAction()
    {

      $ft_request = new FtRequest();
      $ft_request->setEmail($_POST['email']);
      $ft_request->setUrl($_POST['url']);
      $ft_request->setCreated(new \DateTime('now'));

      $em = $this->getDoctrine()->getEntityManager();
      $em->persist($ft_request);
      $em->flush();

      $product = new FreeProduct();
      $product->setEmail($_POST['email']);
      $product->setUrl($_POST['url']);
      $product->setCreated(new \DateTime('now'));
      $product->setFtRequestId($ft_request->getId());

      $em2 = $this->getDoctrine()->getEntityManager();
      $em2->persist($product);
      $em2->flush();

      //email support@ft with details.
      $message = \Swift_Message::newInstance()
        ->setSubject('FrontendTest Request')
        ->setFrom('support@frontendtest.com')
        ->setTo('support@frontendtest.com')
        ->setBody($this->renderView('FtHomeBundle:FtRequest:email.html.twig', array('email' => $_POST['email'], 'url' => $_POST['url'])));

      $this->get('mailer')->send($message);

      $response = $this->render('FtHomeBundle:FtRequest:index.txt.twig');
      $response->headers->set('Content-Type', 'text/plain');
      return $response;

    }


}

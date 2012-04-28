<?php

namespace Ft\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ft\HomeBundle\Entity\FtRequest;
use Ft\HomeBundle\Form\FtRequestType;
use Ft\HomeBundle\FtRequest\FtHelper;

use Ft\CoreBundle\CoreTest\HTML5;
use Ft\CoreBundle\CoreTest\HTML;
use Ft\CoreBundle\CoreTest\Script;
use Ft\CoreBundle\CoreTest\Filedata;
use Ft\CoreBundle\CoreTest\Content;
use Ft\CoreBundle\Entity\TestResult;
use Ft\CoreBundle\CoreTest\Helper;

/**
 * FtRequest controller.
 *
 * @Route("/ft_request")
 */
class FtRequestController extends Controller
{

	public function previewAction($id)
	{	
		$em = $this->getDoctrine()->getEntityManager();
        $ft_request = $em->getRepository('FtHomeBundle:FtRequest')->findOneById($id);
		
		$repository = $this->getDoctrine()
		    ->getRepository('FtCoreBundle:TestResult');
		
		$query = $repository->createQueryBuilder('p')
		    ->where('p.ft_request_id = :ft_request_id and p.weight >= :weight')
			->setParameter('ft_request_id', $id)
		    ->setParameter('weight', $this->container->getParameter('minor_issue_threshold'))
		    ->orderBy('p.weight', 'DESC')
		    ->getQuery();
		
        $results = $query->getResult();

		$summary = $ft_request->getReportSummary();
		
	    //if (!$results) {
	        //throw $this->xxxXXX('There are no results.');
	    //}
		//$cid =  'http://www.frontendtest.com/img/logo_sm.png';

		//$cid =  'http://www.frontendtest.com/img/logo_sm.png';
		$cid =  'http://localhost/frontendtest/web/img/logo_sm.png';
		
        $response = $this->render('FtCoreBundle:Report:email.html.twig', array('cid' => $cid, 'date' => date("D M j G:i:s T Y"), 'url' => $ft_request->getUrl(), 'email' => $ft_request->getEmail(),'results' => $results, 'summary' => $summary));
        $response->headers->set('Content-Type', 'text/html');
        return $response;
	    
	}


	public function deliverAction($id)
	{	
		$em = $this->getDoctrine()->getEntityManager();
        $ft_request = $em->getRepository('FtHomeBundle:FtRequest')->findOneById($id);

		$repository = $this->getDoctrine()
		    ->getRepository('FtCoreBundle:TestResult');
		
		$query = $repository->createQueryBuilder('p')
		    ->where('p.ft_request_id = :ft_request_id and p.weight >= :weight')
			->setParameter('ft_request_id', $id)
		    ->setParameter('weight', $this->container->getParameter('minor_issue_threshold'))
		    ->orderBy('p.weight', 'DESC')
		    ->getQuery();
		
        $results = $query->getResult();

		$summary = $ft_request->getReportSummary();
		
	    //if (!$results) {
	        //throw $this->xxxXXX('There are no results.');
	    //}
		
	    //email report
	    $message = \Swift_Message::newInstance();

		//$cid = $message->embed(\Swift_Image::fromPath('http://www.frontendtest.com/img/logo_sm.png'));
		$cid = $message->embed(\Swift_Image::fromPath('http://localhost/frontendtest/web/img/logo_sm.png'));

	    $message->setSubject('FrontendTest Report')
	       ->setFrom('support@frontendtest.com')
	       ->setTo($ft_request->getEmail())
	  	   ->setContentType('text/html')
	       ->setBody($this->renderView('FtCoreBundle:Report:email.html.twig', array('cid' => $cid, 'date' => date("D M j G:i:s T Y"), 'url' => $ft_request->getUrl(), 'email' => $ft_request->getEmail(), 'results' => $results, 'summary' => $summary)));

	    $this->get('mailer')->send($message);
	
	    $ft_request->setDelivered(new \DateTime('now'));
	    $em->persist($ft_request);
	    $em->flush();
	
		$cid_local =  '../../../web/img/logo_sm.png';
		
        $response = $this->render('FtCoreBundle:Report:email.html.twig', array('cid' => $cid_local, 'date' => date("D M j G:i:s T Y"), 'url' => $ft_request->getUrl(), 'email' => $ft_request->getEmail(), 'results' => $results, 'summary' => $summary));
        $response->headers->set('Content-Type', 'text/html');
        return $response;
	    
	}

	public function adminRunAction($id)
	{	
		error_log('in admin run.');
		$this->runAction($id);			
        return $this->redirect($this->generateUrl('ft_request'));		
	}
	
	public function runAction($id)
	{
		global $ft_url;
		global $ft_data;
		global $ft_host;
		global $ft_http_request;
		
        $em = $this->getDoctrine()->getEntityManager();
        $ft_request = $em->getRepository('FtHomeBundle:FtRequest')->findOneById($id);

		$ft_url = $ft_request->getUrl();
		$ft_data = FtHelper::setFtData($ft_url);
		
		//global $ft_http_request['request_header'] should be set:
		FtHelper::setMiscFtGlobals($ft_http_request['request_header']);

		FtHelper::testHttpCode($ft_http_request);		
		FtHelper::testContentType($ft_http_request);		
		//FtHelper::testMinContentLength($ft_http_request);	
		FtHelper::setFtDom($ft_url);
		
		$suiteActionRes = $this->suiteAction($ft_request);	

		$ft_request->setFtScoreB($suiteActionRes);
		
		$top_weight_sample = 7;
		$top_weight_sum = 0;		
		//use top weighted results to get auto score
		$query = $em->createQuery('select c.weight from Ft\CoreBundle\Entity\TestResult c where c.ft_request_id = ' .$id . ' ORDER BY c.weight desc ')->setMaxResults($top_weight_sample);;
		$results = $query->getResult();
		foreach($results as $result) {
			$top_weight_sum = $top_weight_sum + $result['weight'];
		}
		
		$score = $top_weight_sum/$top_weight_sample;
		
		$ft_request->setFtScoreA($score);
		
		$format1 = ' %s However, you may want to consider ';
		$format2 = ' %s Nonetheless, we suggest ';
		$format3 = ' %s We strongly suggest ';
        
		$adjective_str ="";
		//just hard-code report summary adjective here FOR NOW. 
		switch ($score) {
		    case ($score < 8):
		        $adjective_str = sprintf($format1, 'is **nearly perfect**!!');
		        break;
		    case ($score < 12):
		        $adjective_str = sprintf($format1, 'is **excellent**!');
		        break;
		    case ($score < 16):
		        $adjective_str = sprintf($format2, 'is **very good**.');
		        break;
		    case ($score < 20):
		        $adjective_str = sprintf($format2, 'is **good**.');
		        break;
		    case ($score < 25):
		        $adjective_str = sprintf($format3, '**could use some love**.');
		        break;
		    case ($score < 35):
		        $adjective_str = sprintf($format3, '**can be improved**.');
		        break;
		    default:
		        $adjective_str = sprintf($format3, '**can be much improved**.');
		        break;		
		}
				
		if($adjective_str != '') {			
			$report_summary = sprintf('Thank you for using FrontendTest. We reviewed the submitted web site and we have discovered that the front-end code' . $adjective_str . 'making the following improvements, listed in order of priority.' );
			$ft_request->setReportSummary($report_summary);
			//echo $report_summary;
		}
				
	    $em->persist($ft_request);
	    $em->flush();
	
	}
	
    public function goAction()
    {
	
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
      $em->flush();

	  //run this manually for now!!!!!
	
      //$runAction = $this->runAction($ft_request->getId());

      //email support@ft with details.

      $message = \Swift_Message::newInstance()
        ->setSubject('FrontendTest Request')
        ->setFrom('support@frontendtest.com')
        ->setTo('support@frontendtest.com')
        ->setBody($this->renderView('FtHomeBundle:FtRequest:email.html.twig', array('email' => $_POST['email'], 'url' => $_POST['url'])));

      $this->get('mailer')->send($message);

      $response = $this->render('FtHomeBundle:FtRequest:go.txt.twig');
      $response->headers->set('Content-Type', 'text/plain');
      return $response;

    }

	public function suiteAction($ft_request)
    {
		//$suite_time_start = Helper::microtime_float();
		$suite_time_start = time();
		//query the core test table to determine which tests to run?
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('FtCoreBundle:CoreTest')->findBy(
		    array('run_by_default' => 1)
		);

		//run whichever tests it can find.
		//depending on outcome, result will be saved for request, with custom data injected
		$HTML5 = new HTML5();
		$HTML = new HTML();
		$Script = new Script();
		$Filedata = new Filedata();
		$Content = new Content();
		
        foreach($entities as $entity) {
			$test_time_start = Helper::microtime_float();
			
			$result_instance = null;
			//var_dump($entity);
			//do we have the class?
			$packageName = $entity->getPackageName();			
			$className = $entity->getClassName();

			//if a record already exists for that test name (class) don't run that test.
			$ex_result = $em->getRepository('FtCoreBundle:TestResult')->findOneBy(array('ft_request_id' => $ft_request->getId(), 'class_name' => $entity->getClassName()));
			if($ex_result) { continue; }
			//error_log('$ex_result ' . var_dump($ex_result));
			
			//THIS IS A POOR WAY TO CHECK IF THE TESTS EXIST. WHAT IF THE SAME TEST NAME EXISTS IN TWO "PACKAGES"?
			if(method_exists($HTML5,$className) || method_exists($HTML,$className) || method_exists($Script,$className) || method_exists($Filedata,$className) || method_exists($Content,$className)){ 
			//try {
				$result_instance = ${$packageName}->$className();
			//} catch (Exception $e) {	
			//	echo 'could not run test '.$entity->getPackageName().':'.$entity->getClassName();
			//	continue;
			//}
			}
		
			//if result instance is not false, then add to result_array.
			if($result_instance) {
				//merge what is returned with the coretest record. add to result array.
				//persist test result
			    error_log($entity->getClassName().' true.');
		        $result = new TestResult();
		        $result->setWeight($entity->getWeight());					
		        $result->setClassName($entity->getClassName());	
		        $result->setKind($entity->getPackageName());	
		        $result->setFtRequest($ft_request);	
		        $em->persist($result);	
											
				if(is_bool($result_instance)) {					
				
			        $result->setBody($entity->getDescription());										
			        $result->setHeading($entity->getHeading());					
							
				} elseif(is_array($result_instance)) {
					//in this case, we need to take every array value that is returned and substitute %X% in the template.
					$coretestDesc = $entity->getDescription();
					$coretestHead = $entity->getHeading();
					$index = 1;

					foreach($result_instance as $str_to_insert) {
						$str_to_substitue = '%'.$index.'%';
						$coretestDesc = str_replace($str_to_substitue, $str_to_insert, $coretestDesc);
						$coretestHead = str_replace($str_to_substitue, $str_to_insert, $coretestHead);
						$index++;
					}
					$result->setBody($coretestDesc);
					$result->setHeading($coretestHead);
					 
				}
			}  else {
				error_log($entity->getClassName().' false.');
			}
			$test_time_end = Helper::microtime_float();
			$test_time = $test_time_end - $test_time_start;

			error_log($test_time . ' seconds.');	
			
		}	
				
		$em->flush();
		
		$suite_time_end = time();
		$suite_time = $suite_time_end - $suite_time_start;

		error_log("\n\n");
		error_log($suite_time . ' seconds. (SUITE)');	

		return $suite_time;	
	}

    /**
     * Lists all FtRequest entities.
     *
     * @Route("/", name="ft_request")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
	
		$query = $em->createQuery('SELECT p FROM FtHomeBundle:FtRequest p ORDER BY p.created DESC')->setMaxResults(30);

		$entities = $query->getResult();
		
        return array('entities' => $entities);
    }

    /**
     * Finds and displays a FtRequest entity.
     *
     * @Route("/{id}/show", name="ft_request_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtHomeBundle:FtRequest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FtRequest entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        
		);
    }

    /**
     * Displays a form to create a new FtRequest entity.
     *
     * @Route("/new", name="ft_request_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FtRequest();

        $entity->setCreated(new \DateTime());
        $form   = $this->createForm(new FtRequestType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new FtRequest entity.
     *
     * @Route("/create", name="ft_request_create")
     * @Method("post")
     * @Template("FtHomeBundle:FtRequest:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new FtRequest();
        $request = $this->getRequest();
        $form    = $this->createForm(new FtRequestType(), $entity);

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            //return $this->redirect($this->generateUrl('ft_request_show', array('id' => $entity->getId())));
            return $this->redirect($this->generateUrl('ft_request'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing FtRequest entity.
     *
     * @Route("/{id}/edit", name="ft_request_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtHomeBundle:FtRequest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FtRequest entity.');
        }

        $editForm = $this->createForm(new FtRequestType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );

    }

    /**
     * Edits an existing FtRequest entity.
     *
     * @Route("/{id}/update", name="ft_request_update")
     * @Method("post")
     * @Template("FtHomeBundle:FtRequest:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtHomeBundle:FtRequest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FtRequest entity.');
        }

        $entity->setUpdated(new \DateTime());

        $editForm   = $this->createForm(new FtRequestType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            //return $this->redirect($this->generateUrl('ft_request_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('ft_request'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FtRequest entity.
     *
     * @Route("/{id}/delete", name="ft_request_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('FtHomeBundle:FtRequest')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FtRequest entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ft_request'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

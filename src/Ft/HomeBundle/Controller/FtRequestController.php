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
use Ft\CoreBundle\Entity\TestResult;
use Ft\CoreBundle\CoreTest\Helper;

/**
 * FtRequest controller.
 *
 * @Route("/ft_request")
 */
class FtRequestController extends Controller
{
    /**
     * Lists all FtRequest entities.
     *
     * @Route("/", name="ft_request_go")
     * @Template()
     */

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
		
		$suiteAction = $this->suiteAction($ft_request);		
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

      $runAction = $this->runAction($ft_request->getId());

      //email support@ft with details.
/*
      $message = \Swift_Message::newInstance()
        ->setSubject('FrontendTest Request')
        ->setFrom('support@frontendtest.com')
        ->setTo('support@frontendtest.com')
        ->setBody($this->renderView('FtHomeBundle:FtRequest:email.html.twig', array('email' => $_POST['email'], 'url' => $_POST['url'])));

      $this->get('mailer')->send($message);
*/
      $response = $this->render('FtHomeBundle:FtRequest:go.txt.twig');
      $response->headers->set('Content-Type', 'text/plain');
      return $response;

    }

	public function suiteAction($ft_request)
    {
		//query the core test table to determine which tests to run?
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('FtCoreBundle:CoreTest')->findAll();

		//run whichever tests it can find.
		//depending on outcome, result will be saved for request, with custom data injected
		$HTML5 = new HTML5();
		$HTML = new HTML();
		$Script = new Script();
		
        foreach($entities as $entity) {
			$result_instance = null;
			//var_dump($entity);
			//do we have the class?
			$packageName = $entity->getPackageName();			
			$className = $entity->getClassName();

			//if a record already exists for that test name (class) don't run that test.
			$ex_result = $em->getRepository('FtCoreBundle:TestResult')->findOneBy(array('ft_request_id' => $ft_request->getId(), 'class_name' => $entity->getClassName()));
			if($ex_result) { 
				continue; 
			}
			
			//THIS IS A POOR WAY TO CHECK IF THE TESTS EXIST. WHAT IF THE SAME TEST NAME EXISTS IN TWO "PACKAGES"?
			if(method_exists($HTML5,$className) || method_exists($HTML,$className) || method_exists($Script,$className)){ 
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
				//echo '<br>'.$entity->getClassName().' false.';
			}
	
			
		}	
		
		$em->flush();
		
	
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

        $entities = $em->getRepository('FtHomeBundle:FtRequest')->findAll();

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
            'delete_form' => $deleteForm->createView(),        );
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

            return $this->redirect($this->generateUrl('ft_request_show', array('id' => $entity->getId())));
            
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

        $editForm   = $this->createForm(new FtRequestType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ft_request_edit', array('id' => $id)));
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

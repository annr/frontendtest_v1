<?php

namespace Ft\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ft\CoreBundle\Entity\TestResult;
use Ft\CoreBundle\Form\TestResultType;

/**
 * TestResult controller.
 *
 * @Route("/test_result_count/{ft_request_id}")
 */
class TestResultController extends Controller
{

    public function filterAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

		$entities = $em->getRepository('FtCoreBundle:TestResult')->findBy(
		    array('ft_request_id' => $id),
		    array('weight' => 'DESC')
		);
		
        return $this->render('FtCoreBundle:TestResult:filter.html.twig', array('entities' => $entities));
    }

    public function averageWeightAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

		$query = $em->createQuery('select avg(c.weight) from Ft\CoreBundle\Entity\TestResult c where c.ft_request_id = ' .$id);
		
		$num = $query->getResult();

        return $this->render('FtCoreBundle:TestResult:averageWeight.txt.twig', array('number_results' => round($num[0][1],2)));

    }

    public function countAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('select count(c.id) from Ft\CoreBundle\Entity\TestResult c where c.ft_request_id = ' .$id);		
		$num = $query->getResult();

		$query = $em->createQuery('select count(c.id) from Ft\CoreBundle\Entity\TestResult c where c.ft_request_id = ' .$id .' and c.weight >= ' . $this->container->getParameter('minor_issue_threshold'));		
		$num_major = $query->getResult();

		$query = $em->createQuery('select count(c.id) from Ft\CoreBundle\Entity\TestResult c where c.ft_request_id = ' .$id .' and c.weight < ' . $this->container->getParameter('minor_issue_threshold'));		
		$num_minor = $query->getResult();
		
        return $this->render('FtCoreBundle:TestResult:count.txt.twig', array('number_results' => $num[0][1], 'number_major' => $num_major[0][1], 'number_minor' => $num_minor[0][1]));

    }

	//allow minor issue to reach report threshold
    public function promoteAction($id,$ft_request_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtCoreBundle:TestResult')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TestResult entity.');
        }

		$entity->setWeight($entity->getWeight() + 5);
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('test_result_filter', array('id' => $ft_request_id)));

    }

	//allow minor issue to reach report threshold
    public function demoteAction($id,$ft_request_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtCoreBundle:TestResult')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TestResult entity.');
        }

		$entity->setWeight(0);
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('test_result_filter', array('id' => $ft_request_id)));

    }

    /**
     * Lists all TestResult entities.
     *
     * @Route("/", name="test_result")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FtCoreBundle:TestResult')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a TestResult entity.
     *
     * @Route("/{id}/show", name="test_result_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtCoreBundle:TestResult')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TestResult entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new TestResult entity.
     *
     * @Route("/new", name="test_result_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TestResult();
        $form   = $this->createForm(new TestResultType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new TestResult entity.
     *
     * @Route("/create", name="test_result_create")
     * @Method("post")
     * @Template("FtCoreBundle:TestResult:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new TestResult();
        $request = $this->getRequest();
        $form    = $this->createForm(new TestResultType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('test_result'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing TestResult entity.
     *
     * @Route("/{id}/edit", name="test_result_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtCoreBundle:TestResult')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TestResult entity.');
        }

        $editForm = $this->createForm(new TestResultType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing TestResult entity.
     *
     * @Route("/{id}/update", name="test_result_update")
     * @Method("post")
     * @Template("FtCoreBundle:TestResult:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtCoreBundle:TestResult')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TestResult entity.');
        }

        $editForm   = $this->createForm(new TestResultType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('test_result_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a TestResult entity.
     *
     * @Route("/{id}/delete", name="test_result_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('FtCoreBundle:TestResult')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TestResult entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('test_result'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

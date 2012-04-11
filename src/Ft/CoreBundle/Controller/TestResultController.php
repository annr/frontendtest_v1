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
 * @Route("/test_result")
 */
class TestResultController extends Controller
{
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

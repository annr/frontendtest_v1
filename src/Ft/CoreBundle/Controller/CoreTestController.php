<?php

namespace Ft\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ft\CoreBundle\Entity\CoreTest;
use Ft\CoreBundle\Form\CoreTestType;

/**
 * CoreTest controller.
 *
 * @Route("/core_test")
 */
class CoreTestController extends Controller
{
    /**
     * Lists all CoreTest entities.
     *
     * @Route("/", name="core_test")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FtCoreBundle:CoreTest')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a CoreTest entity.
     *
     * @Route("/{id}/show", name="core_test_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtCoreBundle:CoreTest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CoreTest entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new CoreTest entity.
     *
     * @Route("/new", name="core_test_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CoreTest();
        $form   = $this->createForm(new CoreTestType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new CoreTest entity.
     *
     * @Route("/create", name="core_test_create")
     * @Method("post")
     * @Template("FtCoreBundle:CoreTest:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new CoreTest();
        $request = $this->getRequest();
        $form    = $this->createForm(new CoreTestType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('core_test_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing CoreTest entity.
     *
     * @Route("/{id}/edit", name="core_test_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtCoreBundle:CoreTest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CoreTest entity.');
        }

        $editForm = $this->createForm(new CoreTestType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing CoreTest entity.
     *
     * @Route("/{id}/update", name="core_test_update")
     * @Method("post")
     * @Template("FtCoreBundle:CoreTest:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtCoreBundle:CoreTest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CoreTest entity.');
        }

        $editForm   = $this->createForm(new CoreTestType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('core_test_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a CoreTest entity.
     *
     * @Route("/{id}/delete", name="core_test_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('FtCoreBundle:CoreTest')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CoreTest entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('core_test'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

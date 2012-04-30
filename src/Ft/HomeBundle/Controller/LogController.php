<?php

namespace Ft\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ft\HomeBundle\Entity\Log;

/**
 * Log controller.
 *
 * @Route("/log")
 */
class LogController extends Controller
{
    /**
     * Lists all Log entities.
     *
     * @Route("/", name="log")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FtHomeBundle:Log')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Log entity.
     *
     * @Route("/{id}/show", name="log_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FtHomeBundle:Log')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Log entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

}

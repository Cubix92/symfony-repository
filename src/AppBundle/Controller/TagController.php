<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Note;

/**
 * Tag controller.
 *
 * @Route("/tag")
 */
class TagController extends Controller
{

    /**
     * Lists Tag entities.
     *
     * @Route("/{page}", requirements={"page" = "/d+"}, defaults={"page" = 1}, name="tag_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('tag/index.html.twig', array(
            'tags' => $em->getRepository('AppBundle:Tag')->search($request->query->all()),
            'page' => $page,
            'total' => $em->getRepository('AppBundle:Tag')->getTotalTags()
        ));
    }

}

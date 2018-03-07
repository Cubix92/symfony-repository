<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Log;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LogController extends Controller
{
    /**
     * @Route("/logs", name="logs")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository(Log::class,'logger');

        return $this->render('log/index.html.twig', [
            'logs' => $em->findAll()
        ]);
    }
}

<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Note;

/**
 * Note controller.
 *
 * @Route("/note")
 */
class NoteController extends Controller
{

    /**
     * Searches Note entities.
     *
     * @Route("/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="note_index"),
     * @Method("GET")
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('note/index.html.twig', array(
            'categories' => $em->getRepository('AppBundle:Category')->findAll(),
            'tags' => $em->getRepository('AppBundle:Tag')->findAll(),
            'notes' => $em->getRepository('AppBundle:Note')->search($request->query->all(), $page),
            'page' => $page,
            'total' => $em->getRepository('AppBundle:Note')->getTotalNotes()
        ));
    }

    /**
     * Creates a new Note entity.
     *
     * @Route("/new", name="note_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $note = new Note();
        $form = $this->createForm('AppBundle\Form\NoteType', $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($note);
            $em->flush();

            $this->addFlash('success', 'Notatka została dodana.');
            $this->get('app.logger')->log('Nowa notatka została dodana.');

            return $this->redirectToRoute('note_index');
        }

        return $this->render('note/new.html.twig', array(
            'note' => $note,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Note entity.
     *
     * @Route("/{id}/edit", name="note_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Note $note)
    {
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createForm('AppBundle\Form\NoteType', $note);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($note);
            $em->flush();

            $this->addFlash('success', 'Notatka została zmodyfikowana.');
            $this->get('app.logger')->log("Notatka nr {$note->getId()} została zaktualizowana.");

            return $this->redirectToRoute('note_index');
        }

        return $this->render('note/edit.html.twig', array(
            'note' => $note,
            'form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Note entity.
     *
     * @Route("/{id}/delete", name="note_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Note $note)
    {
        $em = $this->getDoctrine()->getManager();

        $this->get('app.logger')->log("Notatka nr {$note->getId()} została usunięta.");
        $em->remove($note);
        $em->flush();

        $this->addFlash('success', 'Notatka została usunięta.');

        return $this->redirectToRoute('note_index');
    }
}

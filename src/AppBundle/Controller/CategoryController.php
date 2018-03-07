<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     * @Route("/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="category_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('category/index.html.twig', array(
            'categories' => $em->getRepository('AppBundle:Category')->search($request->query->all()),
            'page' => $page,
            'total' => $em->getRepository('AppBundle:Category')->getTotalCategories()
        ));
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/new", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Kategoria została dodana.');
            $this->get('app.logger')->log("Nowa kategoria została dodana.");

            return $this->redirectToRoute('category_index', array('id' => $category->getId()));
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Category entities.
     *
     * @Route("/{id}/show", name="category_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('category/show.html.twig', array(
            'category' => $category
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Kategoria została zmodyfikowana.');
            $this->get('app.logger')->log("Kategoria nr {$category->getId()} została zaktualizowana.");

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}/delete", name="category_delete")
     * @Method("GET")
     */
    public function deleteAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('app.logger')->log("Kategoria nr {$category->getId()} została usunięta.");
        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'Kategoria została usunięta.');

        return $this->redirectToRoute('category_index');
    }

}

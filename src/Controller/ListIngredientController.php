<?php

namespace App\Controller;

use App\Entity\ListIngredient;
use App\Form\ListIngredientType;
use App\Repository\ListIngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/list/ingredient")
 */
class ListIngredientController extends AbstractController
{
    /**
     * @Route("/", name="list_ingredient_index", methods={"GET"})
     */
    public function index(ListIngredientRepository $listIngredientRepository): Response
    {
        return $this->render('list_ingredient/index.html.twig', [
            'list_ingredients' => $listIngredientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="list_ingredient_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $listIngredient = new ListIngredient();
        $form = $this->createForm(ListIngredientType::class, $listIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($listIngredient);
            $entityManager->flush();

            return $this->redirectToRoute('list_ingredient_index');
        }

        return $this->render('list_ingredient/new.html.twig', [
            'list_ingredient' => $listIngredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="list_ingredient_show", methods={"GET"})
     */
    public function show(ListIngredient $listIngredient): Response
    {
        return $this->render('list_ingredient/show.html.twig', [
            'list_ingredient' => $listIngredient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="list_ingredient_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ListIngredient $listIngredient): Response
    {
        $form = $this->createForm(ListIngredientType::class, $listIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_ingredient_index');
        }

        return $this->render('list_ingredient/edit.html.twig', [
            'list_ingredient' => $listIngredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="list_ingredient_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ListIngredient $listIngredient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$listIngredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($listIngredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('list_ingredient_index');
    }
}

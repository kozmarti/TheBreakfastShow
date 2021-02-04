<?php

namespace App\Controller;

use App\Entity\Measure;
use App\Form\MeasureType;
use App\Repository\MeasureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/measure")
 */
class MeasureController extends AbstractController
{
    /**
     * @Route("/", name="measure_index", methods={"GET"})
     */
    public function index(MeasureRepository $measureRepository): Response
    {
        return $this->render('measure/index.html.twig', [
            'measures' => $measureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="measure_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $measure = new Measure();
        $form = $this->createForm(MeasureType::class, $measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($measure);
            $entityManager->flush();

            return $this->redirectToRoute('measure_index');
        }

        return $this->render('measure/new.html.twig', [
            'measure' => $measure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="measure_show", methods={"GET"})
     */
    public function show(Measure $measure): Response
    {
        return $this->render('measure/show.html.twig', [
            'measure' => $measure,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="measure_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Measure $measure): Response
    {
        $form = $this->createForm(MeasureType::class, $measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('measure_index');
        }

        return $this->render('measure/edit.html.twig', [
            'measure' => $measure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="measure_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Measure $measure): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($measure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('measure_index');
    }
}

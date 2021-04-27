<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use App\Service\Slug;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/episode")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/", name="episode_index", methods={"GET"})
     */
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="episode_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slug $slug): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chosenSeason = $episode->getSeason();
            $number = count($chosenSeason->getEpisodes()) + 1;
            $episode->setNumber($number);

            $episode->setSlug($slug->slugify($episode->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($episode);
            $entityManager->flush();

            return $this->redirectToRoute('list_ingredient_new');
        }

        return $this->render('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
            'aboutme' => false,  'funfacts' => false,  'recipes' => true, 'login' => false
        ]);
    }

    /**
     * @Route("/{slug}", name="episode_show", methods={"GET"})
     * @ParamConverter("episode", options={"mapping": {"slug": "slug"}})
     */

    public function show(Request $request,Episode $episode): Response
    {
        $multiplier = $request->request->all();
        if ($multiplier) {
            $multiplier=  $multiplier['quantity'];
        } else {
            $multiplier=0;
        }

        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
            'multiplier' => $multiplier,
            'aboutme' => false,  'funfacts' => false,  'recipes' => true, 'login' => false]);
    }

    /**
     * @Route("/{id}/edit", name="episode_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Episode $episode): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipes_home');
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="episode_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Episode $episode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('episode_index');
    }
}

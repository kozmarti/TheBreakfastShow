<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/season")
 */
class SeasonController extends AbstractController
{

    /**
     * @Route("/new", name="season_new", methods={"GET","POST"})
     */
    public function new(Request $request, SeasonRepository $seasonRepository): Response
    {
        $season = new Season();
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);
        $nextSeasonNumber = count($seasonRepository->findAll()) + 1;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $nextSeasonNumber = count($seasonRepository->findAll()) + 1;
            $season->setNumber($nextSeasonNumber);
            $season->setSlug('season-'.$nextSeasonNumber.'-'.strtolower($season->getTitle()));
            $entityManager->persist($season);
            $entityManager->flush();

            return $this->redirectToRoute('season_show', array('slug' => $season->getSlug()));
        }

        return $this->render('season/new.html.twig', [
            'next_season_number' => $nextSeasonNumber,
            'season' => $season,
            'form' => $form->createView(),
            'aboutme' => false,  'funfacts' => false,  'recipes' => true, 'login' => false
        ]);
    }

    /**
     * @Route("/{slug}", name="season_show", methods={"GET"})
     * @ParamConverter("season", options={"mapping": {"slug": "slug"}})
     */
    public function show(Season $season, EpisodeRepository $episodeRepository, SeasonRepository $seasonRepository): Response
    {
        $episodes=$episodeRepository->findBy(['season'=>$season]);
        $seasons = $seasonRepository->findAll();
        return $this->render('season/show.html.twig', [
            'season' => $season,
            'episodes' => $episodes,
            'seasons' => $seasons,
            'aboutme' => false,  'funfacts' => false,  'recipes' => true, 'login' => false]);
    }

    /**
     * @Route("/{id}/edit", name="season_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Season $season): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('season_index');
        }

        return $this->render('season/edit.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="season_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Season $season): Response
    {
        if ($this->isCsrfTokenValid('delete'.$season->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($season);
            $entityManager->flush();
        }

        return $this->redirectToRoute('welcome');
    }
}

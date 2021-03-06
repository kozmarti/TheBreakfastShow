<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use App\Repository\FavoriteRepository;
use App\Repository\IngredientRepository;
use App\Repository\SeasonRepository;
use App\Service\Slug;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/episode")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/new", name="episode_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Slug $slug, IngredientRepository $ingredientRepository): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($episode->getListIngredients() as $listIngredient) {
                $ingredient = $ingredientRepository->findOneBy(['name' => $listIngredient->getIngredient()->getName()]);
                if ($ingredient) {
                    $listIngredient->setIngredient($ingredient);
                }
                $listIngredient->setEpisode($episode);
            }

            $chosenSeason = $episode->getSeason();
            $number = count($chosenSeason->getEpisodes()) + 1;
            $episode->setNumber($number);

            $episode->setSlug($slug->slugify($episode->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($episode);
            $entityManager->flush();
            return $this->redirectToRoute('season_show', array('slug' => ($episode->getSeason()->getSlug())));
        }

        return $this->render('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
            'aboutme' => false,  'funfacts' => false,  'recipes' => true, 'login' => false, 'myrecipes' => false
        ]);
    }

    /**
     * @Route("/{slug}", name="episode_show", methods={"GET"})
     * @ParamConverter("episode", options={"mapping": {"slug": "slug"}})
     */

    public function show(Request $request,Episode $episode, FavoriteRepository $favoriteRepository,SeasonRepository $seasonRepository): Response
    {
        $user = $this->getUser();
        $isFavorite = $favoriteRepository->findOneBy(['user' => $user,'episode' => $episode]);
        $seasons = $seasonRepository->findAll();
        $multiplier = $request->get('quantity');

        if (is_null($multiplier)) {
            $multiplier = 0;
        }elseif ($multiplier < 1 || (floor($multiplier) != $multiplier)){
            $multiplier = 0;
            $this->addFlash(
                'number-warning',
                'Please give a valid number of persons'
            );
        }
        return $this->render('episode/show.html.twig', [
            'seasons' => $seasons,
            'episode' => $episode,
            'multiplier' => $multiplier,
            'is_favorite' => $isFavorite,
            'aboutme' => false,  'funfacts' => false,  'recipes' => true, 'login' => false, 'myrecipes' => false]);
    }

    /**
     * @Route("/{slug}/edit", name="episode_edit", methods={"GET","POST"})
     * @ParamConverter("episode", options={"mapping": {"slug": "slug"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Episode $episode, IngredientRepository $ingredientRepository): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($episode->getListIngredients() as $listIngredient) {
                $ingredient = $ingredientRepository->findOneBy(['name' => $listIngredient->getIngredient()->getName()]);
                if ($ingredient) {
                    $listIngredient->setIngredient($ingredient);
                }
                $listIngredient->setEpisode($episode);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('episode_show', array('slug' => $episode->getSlug()));
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
            'aboutme' => false,  'funfacts' => false,  'recipes' => true, 'login' => false, 'myrecipes' => false]);
    }

    /**
     * @Route("/{id}", name="episode_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Episode $episode): Response
    {
        $actualSeason = $episode->getSeason()->getSlug();
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('season_show', array('slug' => $actualSeason));
    }
}

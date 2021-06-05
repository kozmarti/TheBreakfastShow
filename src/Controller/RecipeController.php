<?php


namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Repository\ImagesRepository;
use App\Repository\EpisodeRepository;
use App\Repository\SeasonRepository;
use App\Repository\MeasureRepository;
use App\Repository\IngredientRepository;
use App\Repository\ListIngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipes", name="recipes_")
 */

class RecipeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository, ListIngredientRepository $listIngredientRepository): Response
    {
        $seasons= $seasonRepository->findAll();
        $episodes = $episodeRepository->findAll();
        return $this->render('home/content/recipes.html.twig',[ 'seasons' => $seasons, 'episodes' => $episodes,
            'aboutme' => false,  'aboutyou' => false,   'funfacts' => false,  'recipes' => true, 'login' => false,'myrecipes' => false]);
    }

    /**
     * @Route("/my-recipes", name="my_recipes")
     */
    public function myrecipes(EpisodeRepository $episodeRepository, FavoriteRepository $favoriteRepository): Response
    {
        $favorites = $favoriteRepository->findBy(['user'=>$this->getUser()]);
        $episode_ids = [];
        foreach ($favorites as $favorite) {
            $episode_ids[] += $favorite->getEpisode();
        }
        $my_episodes = $episodeRepository->findBy(['id' => $episode_ids]);
        return $this->render('home/content/my_recipes.html.twig',[ 'my_episodes' => $my_episodes,
            'aboutme' => false,  'aboutyou' => false,   'funfacts' => false,  'recipes' => false, 'login' => false, 'myrecipes' => true]);
    }





}
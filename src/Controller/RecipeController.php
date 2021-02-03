<?php


namespace App\Controller;

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
        return $this->render('home/content/recipes.html.twig',[ 'seasons' => $seasons, 'episodes' => $episodes]);
    }


}
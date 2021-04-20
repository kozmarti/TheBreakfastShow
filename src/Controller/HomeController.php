<?php


namespace App\Controller;

use App\Repository\ImagesRepository;

use App\Repository\FunFactRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Extra\Markdown\MarkdownExtension;


class HomeController extends AbstractController

{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/home", name="welcome")
     */
    public function welcome(ImagesRepository $imagesRepository): Response
    {
        $images=$imagesRepository->findAll();

        $ownerPhotos=[];
        foreach ($images as $image){
            array_push($ownerPhotos, $image->getOwnerphoto());


        }
        shuffle($ownerPhotos);
        $countOwnerPhotos = count($ownerPhotos);
        return $this->render('home/content/welcome.html.twig', ['images' => $ownerPhotos,'count_photos' =>$countOwnerPhotos,
            'aboutme' => true,   'funfacts' => false,  'recipes' => false, 'login' => false ]);
    }

    /**
     * @Route("/funfact", name="fun_fact")
     */
    public function funfact(FunFactRepository $funFactRepository, IngredientRepository $ingredientRepository): Response
    {
        $funfacts=$funFactRepository->findAll();
        $fact= $funfacts[array_rand($funfacts)];


        return $this->render('home/content/funfact.html.twig', ['random_fact' => $fact, 'actors' => $funfacts,
        'aboutme' => false,   'funfacts' => true,  'recipes' => false,  'login' => false]);
    }
}
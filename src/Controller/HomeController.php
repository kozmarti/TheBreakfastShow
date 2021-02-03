<?php


namespace App\Controller;

use App\Repository\ImagesRepository;

use App\Repository\FunFactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
        return $this->render('home/content/welcome.html.twig', ['images' => $images]);
    }

    /**
     * @Route("/funfact", name="fun_fact")
     */
    public function funfact(FunFactRepository $funFactRepository): Response
    {
        $funfacts=$funFactRepository->findAll();
        $fact= $funfacts[array_rand($funfacts)];

        return $this->render('home/content/funfact.html.twig', ['random_fact' => $fact]);
    }
}
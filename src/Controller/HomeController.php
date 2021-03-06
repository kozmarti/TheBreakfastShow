<?php


namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Favorite;
use App\Entity\FunFact;
use App\Entity\User;
use App\Repository\EpisodeRepository;
use App\Repository\FavoriteRepository;
use App\Repository\ImagesRepository;

use App\Repository\FunFactRepository;
use App\Repository\IngredientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Extra\Markdown\MarkdownExtension;
use Symfony\Component\HttpFoundation\Request;

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
        //@Route("/{_locale}/home", name="welcome")
        $images=$imagesRepository->findAll();

        $ownerPhotos=[];
        foreach ($images as $image){
            array_push($ownerPhotos, $image->getOwnerphoto());


        }
        shuffle($ownerPhotos);
        $countOwnerPhotos = count($ownerPhotos);
        return $this->render('home/content/welcome.html.twig', ['images' => $ownerPhotos,'count_photos' =>$countOwnerPhotos,
            'aboutme' => true,   'funfacts' => false,  'recipes' => false, 'login' => false, 'myrecipes' => false]);
    }

    /**
     * @Route("/funfact", name="fun_fact")
     */
    public function funfact(FunFactRepository $funFactRepository, IngredientRepository $ingredientRepository): Response
    {
        $funfacts=$funFactRepository->findAll();
        $fact= $funfacts[array_rand($funfacts)];

        $end1 = intdiv(count($funfacts),2)-1;
        $start2 =intdiv(count($funfacts),2);
        $end2 = count($funfacts) - 1;
        return $this->render('home/content/funfact.html.twig', ['random_fact' => $fact, 'actors' => $funfacts, 'end1' => $end1, 'end2' => $end2, 'start2' => $start2,
        'aboutme' => false,   'funfacts' => true,  'recipes' => false,  'login' => false, 'myrecipes' => false]);
    }

    /**
     * @Route("/funfact/{funfactname}", name="show_fun_fact")
     */
    public function funfactShow(Request $request, FunFactRepository $funFactRepository, IngredientRepository $ingredientRepository): Response
    {
        $funfact= $request->attributes->get('funfactname');
        $funfactToShow=$funFactRepository->findOneBy(['name' => $funfact]);
        return $this->render('home/content/show_funfact.html.twig', ['random_fact' => $funfactToShow, 'aboutme' => false,   'funfacts' => true,  'recipes' => false,  'login' => false, 'myrecipes' => false]);
    }


    /**
     * @Route("/favorites", name="favorites")
     */
    public function favorites(UserRepository $userRepository, EpisodeRepository $episodeRepository, Request $request, FavoriteRepository $favoriteRepository, EntityManagerInterface $entityManager): Response
    {
        $userId = $request->get('user-id');
        $episodeId = $request->get('episode-id');
        if (is_numeric($userId) && is_numeric($episodeId)) {
            if (!is_null($userRepository->find($userId)) && !is_null($episodeRepository->find($episodeId))) {
                if (is_null($favoriteRepository->findOneBy(['user' => $userId,'episode' => $episodeId]))){
                    $favorite = new Favorite();
                    $favorite->setUser($userId);
                    $favorite->setEpisode($episodeId);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($favorite);
                    $entityManager->flush();
                } else {
                    $favorite = $favoriteRepository->findOneBy(['user' => $userId,'episode' => $episodeId]);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($favorite);
                    $entityManager->flush();
                }
            }
        }

        return $this->json('', 200);

    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     */

    public function search(Request $request, EpisodeRepository $episodeRepository, IngredientRepository $ingredientRepository): Response
    {
        $toSearch=$request->get('to-search');
        $result = $episodeRepository->findBySearch($toSearch);
        return $this->render('home/content/search_result.html.twig', ['episodes' => $result, 'to_search' => $toSearch, 'count_result' => count($result),
        'aboutme' => false,   'funfacts' => false,  'recipes' => false,  'login' => false, 'myrecipes' => false]);
    }

    /**
     * @Route("/upload/redirect/{slug}", name="upload_register_redirect", methods={"GET"})
     */

    public function loginRedirect(Request $request): Response
    {
        $episode= $request->attributes->get('slug');
        $session = new Session();
        $session->set('redirect', true);
        $session->set('slug', $episode);

        return $this->redirectToRoute('app_register');
    }
}
<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Favorite;
use App\Entity\UserPhoto;
use App\Entity\User;
use App\Form\UserPhotoType;
use App\Repository\EpisodeRepository;
use App\Repository\UserPhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/user/photo")
 */
class UserPhotoController extends AbstractController
{
    /**
     * @Route("/all", name="user_photo_all", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function all(UserPhotoRepository $userPhotoRepository): Response
    {

        return $this->render('user_photo/all.html.twig', [
            'user_photos' => $userPhotoRepository->findAllOrderedByApproval(),
            'aboutme' => false, 'funfacts' => false, 'recipes' => false, 'login' => false, 'myrecipes' => false
        ]);
    }

    /**
     * @Route("/approval/{id}", name="photo_approval", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function photoApproval(UserPhotoRepository $userPhotoRepository, Request $request): Response
    {

        $photoToApproveID = $request->attributes->get('id');
        if (is_numeric($photoToApproveID)) {

            if (is_null($userPhotoRepository->findOneBy(['id' => $photoToApproveID]))) {
                return $this->redirectToRoute('user_photo_all');
            } else {

                $isApproved = $userPhotoRepository->findOneBy(['id' => $photoToApproveID])->getIsApproved();
                $photo = $userPhotoRepository->findOneBy(['id' => $photoToApproveID]);
                if ($isApproved) {
                    $photo->setIsApproved(false);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                } else {
                    $photo->setIsApproved(true);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                }
                return $this->redirectToRoute('user_photo_all');
            }
        }
        return $this->render('user_photo/all.html.twig', [
            'user_photos' => $userPhotoRepository->findAllOrderedByApproval(),
            'aboutme' => false, 'funfacts' => false, 'recipes' => false, 'login' => false, 'myrecipes' => false
        ]);
    }

    /**
     * @Route("/{slug}", name="user_photo_index", methods={"GET","POST"})
     * @ParamConverter("episode", options={"mapping": {"slug": "slug"}})
     */
    public function index(Request $request, UserPhotoRepository $userPhotoRepository, Episode $episode): Response
    {

        $userPhoto = new UserPhoto();
        $form = $this->createForm(UserPhotoType::class, $userPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userPhoto->setUser($this->getUser());
            $userPhoto->setEpisode($episode);
            $userPhoto->setIsApproved(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userPhoto);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'Your photo is successfully uploaded. It will appear on the website as soon as the admin approves it.'
            );

            return $this->redirectToRoute('user_photo_index', array('slug' => $episode->getSlug()));
        }


        return $this->render('user_photo/index.html.twig', [
            'episode' => $episode,
            'user_photos' => $userPhotoRepository->findBy(['episode' => $episode, 'isApproved' => true]),
            'form' => $form->createView(),
            'aboutme' => false, 'aboutyou' => false, 'funfacts' => false, 'recipes' => true, 'login' => false, 'myrecipes' => false]);

    }

    /**
     * @Route("/{id}", name="user_photo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserPhoto $userPhoto): Response
    {
        if ($this->getUser()->getUsername() == $userPhoto->getUser()->getUsername()) {
            $slug = $userPhoto->getEpisode()->getSlug();
            if ($this->isCsrfTokenValid('delete' . $userPhoto->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($userPhoto);
                $entityManager->flush();
            }
            return $this->redirectToRoute('user_photo_index', array('slug' => $slug));
        } elseif ($this->getUser()->getRoles()) {
            if ($this->isCsrfTokenValid('delete' . $userPhoto->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($userPhoto);
                $entityManager->flush();
            }
            return $this->redirectToRoute('user_photo_all');
        }
    }



}

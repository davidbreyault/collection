<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        $firstname = $this->getUser()->getFirstname();
        $user = $this->getUser();
        $collections = $user->getAlbum()->toArray();
        //dd($collections);

        return $this->render('profile/index.html.twig', [
            'firstname'         => $firstname,
            'collections'       => $collections
        ]);
    }

    /**
     * @Route("/profile/remove-album/{id}", name="remove_album_from_my_collection")
     */
    public function remove_album_from_my_collection($id): Response
    {
        $album = $this->entityManager->getRepository(Album::class)->find($id);
        $user = $this->getUser();
        $user->removeAlbum($album);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        return $this->redirectToRoute('profile');
    }
}

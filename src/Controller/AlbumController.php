<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\User;
use App\Form\AlbumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AlbumController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/album", name="album")
     */
    public function index(): Response
    {
        $albums = $this->entityManager->getRepository(Album::class)->findAll();
        //sdd($albums);

        return $this->render('album/index.html.twig', [
            'albums' => $albums
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/album/add", name="add_album")
     */
    public function add_album(Request $request): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $album = $form->getData();

                $this->entityManager->persist($album);
                $this->entityManager->flush();
            }

        return $this->render('album/add_album.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/album/add-to-my-collection/{id}", name="add_to_my_collection")
     */
    public function add_to_my_collection($id): Response
    {
        $album = $this->entityManager->getRepository(Album::class)->find($id);
        $user = $this->getUser();
        $user->addAlbum($album);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        return $this->redirectToRoute('album');
    }
}

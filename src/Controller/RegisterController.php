<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // Encoder le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $user->setLastname(strtoupper($user->getLastname()));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre compte a bien été crée !');

            return $this->redirectToRoute('register');
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

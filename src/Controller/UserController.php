<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Service\FileUploader;


#[Route('/members')]
class UserController extends AbstractController
{
    #[Route('/all', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/funds', name: 'app_user_funds', methods: ['GET'])]
    public function funds(UserRepository $userRepository): Response
    {
        return $this->render('user/funds.html.twig', [
            'users' => $userRepository->findBy(['type' => 'fund']   ),
        ]);
    }

    #[Route('/companies', name: 'app_user_companies', methods: ['GET'])]
    public function companies(UserRepository $userRepository): Response
    {
        return $this->render('user/companies.html.twig', [
            'users' => $userRepository->findBy(['type' => 'commerce']   ),
        ]);
    }

    #[Route('/individuals', name: 'app_user_individuals', methods: ['GET'])]
    public function individuals(UserRepository $userRepository): Response
    {
        return $this->render('user/individuals.html.twig', [
            'users' => $userRepository->findBy(['type' => 'individual']   ),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, SluggerInterface $slugger, EntityManagerInterface $entityManager, FileUploader $fileUploader, string $logotypeDirectory): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logotypeFile = $form->get('logotype')->getData();
            if ($logotypeFile) {
                $originalFilename = pathinfo($logotypeFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logotypeFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $logotypeFile->move($logotypeDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $logotype = $fileUploader->upload($logotypeFile);
                $user->setLogotype($logotype);
            }
            $user->setLogotype(
                new File($logotypeDirectory.DIRECTORY_SEPARATOR.$user->getLogotype())
            );
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}

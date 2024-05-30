<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/user', name: 'admin.user')]
class UserController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('Backend/User/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    //update user
    #[route('/{id}/update', name: '.update', methods: ['POST', 'GET'])]
    public function update(?User $user, Request $request): Response|RedirectResponse
    {
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé');

            return $this->redirectToRoute('admin.user.index');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'User mis à jour avec succès');

            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('Backend/User/update.html.twig', [
            'form' => $form,
        ]);
    }

    //delete user
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function deleteProduct(User $user, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $this->em->remove($user);
            $this->em->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token invalide');
        }

        return $this->redirectToRoute('admin.user.index');
    }
}

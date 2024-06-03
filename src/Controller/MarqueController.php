<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;

#[Route('/admin/marque', name: 'admin.marque')]
class MarqueController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }
    
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(MarqueRepository $marqueRepo): Response
    {
        return $this->render('Backend/Marque/index.html.twig', [
            'marques' => $marqueRepo->findAll(),
        ]);
    }

    //create marque
    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $marque = new Marque;

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($marque);
            $this->em->flush();

            $this->addFlash('success', 'Marque créé avec succès');

            return $this->redirectToRoute('admin.marque.index');
        }
        return $this->render('Backend/Marque/create.html.twig', [
            'form' => $form,
        ]);
    }

    //update marque
    #[Route('/{id}/marque', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request, ?Marque $marque): Response|RedirectResponse
    {

        if (!$marque) {
            $this->addFlash('error', 'marque non trouvé');

            return $this->redirectToRoute('admin.marque.index');
        }

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Product modifié avec succès');

            return $this->redirectToRoute('admin.marque.index');
        }

        return $this->render('Backend/Marque/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //delete marque
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request,?Marque $marque): Response|RedirectResponse
    {
        if (!$marque) {
            $this->addFlash('error', 'Marque non trouvé');

            return $this->redirectToRoute('admin.marque.index');
        }

        $this->em->remove($marque);
        $this->em->flush();

        $this->addFlash('success', 'Marque supprimé avec succès');

        return $this->redirectToRoute('admin.marque.index');
    }
}

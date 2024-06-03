<?php

namespace App\Controller;

use App\Entity\Taxe;
use App\Form\TaxeType;
use App\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/taxe', name: 'admin.taxe', methods: ['GET'])]
class TaxeController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    //index taxe
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(TaxeRepository $taxeRepo): Response
    {

        return $this->render('Backend/Taxe/index.html.twig', [
            'taxes' => $taxeRepo->findAll(),
        ]);
    }


    //create a taxe
    #[Route('/create', name: '.create', methods: ['POST', 'GET'])]
    public function create(Request $request): Response|RedirectResponse
    {

        $taxe = new Taxe();

        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($taxe);
            $this->em->flush();

            $this->addFlash('success', 'Taxe ajoutée avec succès');

            return $this->redirectToRoute('admin.taxe.index');
        }

        return $this->render('Backend/Taxe/create.html.twig', [
            'form' => $form,
        ]);
    }

    //update taxe
    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request, ?Taxe $taxe): Response|RedirectResponse
    {
        if (!$taxe) {
            $this->addFlash('error', 'Gender non trouvé');

            return $this->redirectToRoute('admin.gender.index');
        }

        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($taxe);
            $this->em->flush();

            $this->addFlash('success', 'Taxe modifié avec succès');

            return $this->redirectToRoute('admin.taxe.index');
        }

        return $this->render('Backend/Taxe/update.html.twig', [
            'form' => $form,
        ]);
    }
    //delete taxe
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function deleteTaxe(Request $request, ?Taxe $taxe): Response|RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $taxe->getId(), $request->request->get('_token'))) {
            $this->em->remove($taxe);
            $this->em->flush();

            $this->addFlash('success', 'Taxe supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token invalide');
        }

        return $this->redirectToRoute('admin.taxe.index');
    }
}

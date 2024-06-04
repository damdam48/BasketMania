<?php

namespace App\Controller;

use App\Entity\ProductVariant;
use App\Form\ProductVariantType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/productvariant', name: 'admin.productvariant')]
class ProductVariantController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    //index
    #[Route('', name: '.index')]
    public function index(ProductVariantRepository $productVariantRepo): Response
    {
        return $this->render('Backend/ProductVariant/index.html.twig', [
            'productVariants' => $productVariantRepo->findAll(),
        ]);
    }


    // create
    #[Route('/create', name: '.create', methods: ['POST', 'GET'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $productVariant = new ProductVariant;

        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($productVariant);
            $this->em->flush();

            return $this->redirectToRoute('admin.productvariant.index');
        }

        return $this->render('Backend/ProductVariant/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //update
    #[Route('/{id}/update', name: '.update', methods:['GET', 'POST'])]
    public function update(Request $request,?ProductVariant $productVariant): Response|RedirectResponse
    {
        if (!$productVariant) {
            $this->addFlash('error', 'productVariant non trouvé');

            return $this->redirectToRoute('admin.productvariant.index');
        }
        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($productVariant);
            $this->em->flush();

            return $this->redirectToRoute('admin.productvariant.index');
        }
        return $this->render('Backend/ProductVariant/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //delete
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request,?ProductVariant $productVariant)
    {
        if ($this->isCsrfTokenValid('delete'. $productVariant->getId(), $request->request->get('_token'))) {
            $this->em->remove($productVariant);
            $this->em->flush();

            $this->addFlash('success', 'Le product variant a bien été supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token invalide');
        }

        return $this->redirectToRoute('admin.productvariant.index');
    }
}




<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/product', name: 'admin.product')]
class ProductController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }


    #[Route('', name: '.index', methods: ['GET'])]
    public function index(ProductRepository $procductRepo): Response
    {
        return $this->render('Backend/Product/index.html.twig', [
            'product' => $procductRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Product créé avec succès');

            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('Backend/Product/create.html.twig', [
            'form' => $form,
        ]);
    }
}

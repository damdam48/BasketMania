<?php

namespace App\Controller;

use App\Entity\ProductImage;
use App\Form\ProductImageType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/productimage', name: 'admin.productimage')]
class ProductImageController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ){   
    }

    //index
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(ProductImageRepository $productImageRepo): Response
    {
        return $this->render('Backend/ProductImage/index.html.twig', [
            'productImages' => $productImageRepo->findAll(),
        ]);
    }

    //create
    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $productImage = new ProductImage;
        $form = $this->createForm(ProductImageType::class, $productImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($productImage);
            $this->em->flush();
            $this->addFlash('success', 'ProductImage créer avec succès');

            return $this->redirectToRoute('admin.productimage.index');
        }
        return $this->render('Backend/ProductImage/create.html.twig', [
            'form' => $form,
        ]);
    }

    //update
    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request,?ProductImage $productImage): Response|RedirectResponse
    {
        if (!$productImage) {
            $this->addFlash('error', 'ProductImage non trouvé');

            return $this->redirectToRoute('admin.productimage.index');
        }
        $form = $this->createForm(ProductImageType::class, $productImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'ProductImage modifié avec succès');

            return $this->redirectToRoute('admin.productimage.index');
        }
        return $this->render('Backend/ProductImage/update.html.twig', [
            'form' => $form,
        ]);
    }

    //delete
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request,?ProductImage $productImage): Response|RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'. $productImage->getId(), $request->request->get('_token'))) {
            $this->em->remove($productImage);
            $this->em->flush();
            $this->addFlash('success', 'ProductImage supprimé avec succès');

            return $this->redirectToRoute('admin.productimage.index');
        }
        return $this->redirectToRoute('admin.productimage.index');
    }
}

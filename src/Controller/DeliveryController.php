<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[route('/admin/delivery', name: 'admin.delivery')]
class DeliveryController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    //index
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(DeliveryRepository $deliveryRepo): Response
    {
        return $this->render('Backend/Delivery/index.html.twig', [
            'deliverys' => $deliveryRepo->findAll(),
        ]);
    }

    //delivery create
    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $delivery = new Delivery;
        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($delivery);
            $this->em->flush();

            return $this->redirectToRoute('admin.delivery.index');
        }
        return $this->render('Backend/Delivery/create.html.twig', [
            'form' => $form,
        ]);
    }

    //update delivery
    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request,?Delivery $delivery): Response|RedirectResponse
    {
        if (!$delivery) {
            $this->addFlash('error', 'delivery non trouvé');

            return $this->redirectToRoute('admin.delivery.index');
        }
        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($delivery);
            $this->em->flush();

            return $this->redirectToRoute('admin.delivery.index');
        }
        return $this->render('Backend/Delivery/update.html.twig', [
            'form' => $form,
        ]);
    }

    // delete delivery
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function deleteGender(Delivery $delivery, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'. $delivery->getId(), $request->request->get('_token'))) {
            $this->em->remove($delivery);
            $this->em->flush();

            $this->addFlash('success', 'Delivery supprimé avec succès');
        }

        return $this->redirectToRoute('admin.delivery.index');
    }
}

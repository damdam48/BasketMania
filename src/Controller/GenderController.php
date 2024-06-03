<?php

namespace App\Controller;

use App\Form\GenderType;
use App\Entity\Gender;
use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/gender', name: 'admin.gender', methods: ['GET'])]
class GenderController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    //index gender
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(GenderRepository $genderRepo): Response
    {
        return $this->render('Backend/Gender/index.html.twig', [
            'genders' => $genderRepo->findAll(),
        ]);
    }

    //create gender
    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $gender = new Gender;

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($gender);
            $this->em->flush();

            $this->addFlash('success', 'Gender créé avec succès');

            return $this->redirectToRoute('admin.gender.index');
        }

        return $this->render('Backend/Gender/create.html.twig', [
            'form' => $form,
        ]);
    }

    //update gender
    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request,?Gender $gender): Response|RedirectResponse
    {
        if (!$gender) {
            $this->addFlash('error', 'Gender non trouvé');

            return $this->redirectToRoute('admin.gender.index');
        }

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($gender);
            $this->em->flush();

            $this->addFlash('success', 'Gender modifié avec succès');

            return $this->redirectToRoute('admin.gender.index');
        }

        return $this->render('Backend/Gender/update.html.twig', [
            'form' => $form,
        ]);
    }

    //delete gender
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function deleteGender(Gender $gender, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'. $gender->getId(), $request->request->get('_token'))) {
            $this->em->remove($gender);
            $this->em->flush();

            $this->addFlash('success', 'Gender supprimé avec succès');
        }

        return $this->redirectToRoute('admin.gender.index');
    }
}

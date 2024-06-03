<?php

namespace App\Controller;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/model', name: 'admin.model')]
class ModelController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(ModelRepository $modelRepo): Response
    {
        return $this->render('Backend/Model/index.html.twig', [
            'models' => $modelRepo->findAll(),
        ]);
    }

    //create model
    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $model = new Model;

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();

            $this->addFlash('success', 'Model créer avec Success');

            return $this->redirectToRoute('admin.model.index');
        }
        return $this->render('Backend/Model/create.html.twig', [
            'form' => $form,
        ]);
    }

    //update model
    #[Route('/{id}/update', name: '.update', methods:['GET', 'POST'])]
    public function update(Request $request,?Model $model): Response|RedirectResponse
    {
        if (!$model) {
            $this->addFlash('error', 'Model non trouvé');

            return $this->redirectToRoute('admin.model.index');
        }

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();

            $this->addFlash('success', 'Model modifié avec Success');

            return $this->redirectToRoute('admin.model.index');
        }
        return $this->render('Backend/Model/update.html.twig', [
            'form' => $form,
        ]);
    }

    //delete model
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(Request $request,?Model $model): Response|RedirectResponse
    {
        if (!$model) {
            $this->addFlash('error', 'Model non trouvé');

            return $this->redirectToRoute('admin.model.index');
        }

        if ($this->isCsrfTokenValid('delete'. $model->getId(), $request->request->get('_token'))) {
            $this->em->remove($model);
            $this->em->flush();

            $this->addFlash('success', 'Model supprimé avec Success');

            return $this->redirectToRoute('admin.model.index');
        }

        return $this->redirectToRoute('admin.model.index');
    }
}

<?php

namespace App\Controller;

use App\Repository\GenderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/gender', name: 'admin.gender', methods: ['GET'])]
class GenderController extends AbstractController
{
    //index gender
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(GenderRepository $genderRepo): Response
    {
        return $this->render('Backend/Gender/index.html.twig', [
            'genders' => $genderRepo->findAll(),
        ]);
    }

}

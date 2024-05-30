<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControlleurController extends AbstractController
{
    #[Route('/login', name: 'app.login', methods : ['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $email = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'email' => $email,
            'error' => $error,
        ]);
    }

    //registers the user
    #[Route('/register', name: 'app.register', methods : ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            
            $em->persist($user);
            $em->flush();
            
            $this->addFlash('success', 'User créé avec succès');
            
            return $this->redirectToRoute('app.login');
        }
        return $this->render('security/register.html.twig', [
            'form' => $form,
        ]);
    }
}

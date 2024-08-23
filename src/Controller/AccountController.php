<?php

namespace App\Controller;

use App\Form\ModifPasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $this->addFlash(
            'success',
            "Votre MDP a été bien mis à jour."
        );

        $user = $this->getUser();

        $form = $this->createForm(ModifPasswordUserType::class, $user, [
            'passwordHasher'=> $passwordHasher
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash(
                'success',
                "Votre MDP a été bien mis à jour."
            );
        }

        return $this->render('account/password.html.twig', [
            'modifPwd' => $form->createView()
        ]);// Dans ce formulaire $form, on transmet $user et un tableau [] au formulaire ModifPasswordUserType
    }
}

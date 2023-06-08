<?php

namespace App\Controller;

use App\Entity\ChangePassword;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    #[Route('/change-password', name: 'app_change_password')]
    public function index(Request $request, Security $security, UserPasswordHasherInterface $hasher, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $changepass = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changepass);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $actualpass = $form->get('actual_password')->getData();
            $secuser = $security->getUser();
            $user = $userRepository->find(['id' => $secuser->getId()]);
            if($hasher->isPasswordValid($user, $actualpass)){
                $changepass = $form->get('new_password')->getData();
            
                //dd($user);
                $newpwd = $hasher->hashPassword($user, $changepass);
                //dd($newpwd, $changepass, $user);
                $user->setPassword($newpwd);
                $entityManagerInterface->persist($user);
                $entityManagerInterface->flush();
                return $this->render('home/index.html.twig');
            }
            return $this->render('change_password/index.html.twig', [
                'changepass' => $form->createView(),
                'error' => "Incorrect password"
            ]);
        }
        return $this->render('change_password/index.html.twig', [
            'changepass' => $form->createView(),
            'error' => null
        ]);
    }
}

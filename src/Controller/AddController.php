<?php

namespace App\Controller;

use App\Entity\Rule;
use App\Entity\User;
use App\Form\AddRuleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/add')]
class AddController extends AbstractController
{
    #[Route('/rule', name: 'app_add_rule')]
    public function index(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddRuleType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $security->getUser();
            $rule = new Rule();
            $rule->setName($form->get('name')->getData());
            $rule->setDescription($form->get('description')->getData());
            $rule->setAuthor($user);
            $entityManager->persist($rule);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('add/rule/index.html.twig', [
            'controller_name' => 'AddRuleController',
            'form' => $form->createView()
        ]);
    }
}

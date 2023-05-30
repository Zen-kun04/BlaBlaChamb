<?php

namespace App\Controller;

use App\Entity\Rule;
use App\Form\EditRuleSelectorType;
use App\Form\EditRuleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/edit')]
class EditController extends AbstractController
{

    #[Route('/rule', name: 'app_edit_rule')]
    public function index(Request $request): Response {

        $form = $this->createForm(EditRuleSelectorType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $id = $form->get('name')->getData();
            return $this->redirectToRoute('app_edit_rule_id', ['id' => $id]);
        }

        return $this->render('edit/rule/index.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/rule/{id}', name: 'app_edit_rule_id')]
    public function edit_rule_id(string $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $ruleRepository = $entityManager->getRepository(Rule::class);
        $rule = $ruleRepository->find($id);
        if($rule){
            $form = $this->createForm(EditRuleType::class, $rule);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($form->getData());
                $entityManager->flush();
                return $this->redirectToRoute('app_profile');
            }
        }
        
        return $this->render('edit/rule/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

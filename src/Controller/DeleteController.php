<?php

namespace App\Controller;

use App\Entity\Rule;
use App\Form\DeleteRuleType;
use App\Form\SelectRuleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/delete')]
class DeleteController extends AbstractController
{

    #[Route('/rule', name: 'app_delete_rule')]
    public function index(Request $request, Security $security, EntityManagerInterface $entityManager) {

        $user = $security->getUser();
        $ruleRepository = $entityManager->getRepository(Rule::class);
        $rules = $ruleRepository->findBy(['author' => $user]);
        $form = $this->createForm(SelectRuleType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $rule = $ruleRepository->find($form->get('name')->getData());
            return $this->redirectToRoute("app_delete_rule_id", ["id" => $rule->getId()]);
        }
        return $this->render('delete/rule/index.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/rule/{id}', name: 'app_delete_rule_id')]
    public function delete(string $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $ruleRepository = $entityManager->getRepository(Rule::class);
        $rule = $ruleRepository->find($id);
        if($rule){
            $form = $this->createForm(DeleteRuleType::class, $rule);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $entityManager->remove($form->getData());
                $entityManager->flush();
                return $this->redirectToRoute('app_profile');
            }

            return $this->render("delete/rule/confirm.html.twig", [
                'rule' => $rule,
                'form' => $form
            ]);
        }

        return $this->redirectToRoute("app_profile");
    }
}
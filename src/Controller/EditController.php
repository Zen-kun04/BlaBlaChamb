<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Rule;
use App\Form\EditRideType;
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

    #[Route('/ride/{id}', name: 'app_edit_ride_id')]
    public function edit_ride_id(string $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $rideRepository = $entityManager->getRepository(Ride::class);
        $ride = $rideRepository->find($id);
        if($ride){
            $form = $this->createForm(EditRideType::class, $ride);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($form->getData());
                $entityManager->flush();
                return $this->redirectToRoute('app_profile');
            }
        }
        
        return $this->render('edit/ride/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

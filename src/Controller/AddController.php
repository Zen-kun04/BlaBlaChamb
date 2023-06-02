<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Rule;
use App\Entity\User;
use App\Form\AddRideType;
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

    #[Route('/ride', name: 'app_add_ride')]
    public function index_ride(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddRideType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $security->getUser();
            $ride = new Ride();
            $ride->setCreated(new \DateTime())
            ->setDate($form->get("date")->getData())
            ->setDeparture($form->get("departure")->getData())
            ->setDestination($form->get("destination")->getData())
            ->setDriver($user)
            ->setPrice($form->get("price")->getData())
            ->setSeats($form->get("seats")->getData());
            $entityManager->persist($ride);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('add/ride/index.html.twig', [
            'controller_name' => 'AddRuleController',
            'form' => $form->createView()
        ]);
    }
}

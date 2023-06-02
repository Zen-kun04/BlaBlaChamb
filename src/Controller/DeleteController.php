<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Rule;
use App\Form\DeleteRuleType;
use App\Form\SelectRideType;
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

    #[Route('/rule/{id}', name: 'app_delete_rule_id')]
    public function delete(string $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $ruleRepository = $entityManager->getRepository(Rule::class);
        $rule = $ruleRepository->find($id);
        if($rule){

            $entityManager->remove($rule);
            $entityManager->flush();
        }

        return $this->redirectToRoute("app_profile");
    }

    #[Route('/ride/{id}', name: 'app_delete_ride_id')]
    public function delete_ride(string $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $rideRepository = $entityManager->getRepository(Ride::class);
        $ride = $rideRepository->find($id);
        if($ride){
            $entityManager->remove($ride);
            $entityManager->flush();
        }

        return $this->redirectToRoute("app_profile");
    }
}
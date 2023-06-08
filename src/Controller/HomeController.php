<?php

namespace App\Controller;

use App\Entity\Random;
use App\Form\RandomType;
use App\Form\SearchAnnouncementsType;
use App\Repository\RideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, RideRepository $rideRepository): Response
    {
        $rides = $rideRepository->findAll();

        $form = $this->createForm(SearchAnnouncementsType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($form->get('date')->getData() !== null){
                $rides = $rideRepository->search($form->get('departure')->getData(), $form->get('destination')->getData(), $form->get('date')->getData());
            }else{
                $rides = $rideRepository->search($form->get('departure')->getData(), $form->get('destination')->getData());
            }
            
        }
        return $this->render('home/index.html.twig', [
            "rides" => $rides,
            "search" => $form->createView(),
            "departure" => $form->get('departure')->getData(),
            "destination" => $form->get('destination')->getData(),
            "date" => $form->get('date')->getData()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Ride;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation_id')]
    public function index(string $id, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $rideRepository = $entityManager->getRepository(Ride::class);
        $ride = $rideRepository->find($id);
        if($ride){
            $form = $this->createForm(ReservationType::class, $ride);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {

                $user = $security->getUser();
                if($ride->getDriver() === $user || $ride->getReservations()->count() >= $ride->getSeats()){
                    // First check: if the driver of the reservation is the user who submitted
                    // Second check: if the amount of reservation is equal or more than the amount of seats
                    return $this->redirectToRoute('app_home');
                }
                foreach ($ride->getReservations() as $reservation) {
                    // Third check: if the user already took a reservation to this ride
                    if($reservation->getPassenger() === $user){
                        return $this->redirectToRoute('app_home');
                    }
                }
                
                $reservation = new Reservation();
                $reservation->setPassenger($user)
                ->setRide($ride)
                ->setCreated(new \DateTime())
                ->setConfirmed(false);
                $entityManager->persist($reservation);
                $entityManager->flush();

                return $this->redirectToRoute('app_home');
            }
            
            
            return $this->render('reservation/index.html.twig', [
                "ride" => $ride,
                "form" => $form->createView()
            ]);
        }
        return $this->redirectToRoute('app_home');
    }
}

<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/confirm')]
class ConfirmController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_confirm_reservation_id')]
    public function index(string $id, ReservationRepository $reservationRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $reservation = $reservationRepository->find($id);
        if($reservation && $reservation->getRide()->getDriver() == $this->getUser()){
            $reservation->setConfirmed(true);
            $entityManagerInterface->persist($reservation);
            $entityManagerInterface->flush();
        }
        return $this->redirectToRoute('app_profile');
    }
}

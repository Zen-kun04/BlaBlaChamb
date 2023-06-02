<?php

namespace App\Controller;

use App\Form\CarInformationType;
use App\Form\RuleInformationType;
use App\Form\UserInformationType;
use App\Repository\CarRepository;
use App\Repository\ReservationRepository;
use App\Repository\RideRepository;
use App\Repository\RuleRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        Request $request,
        Security $security,
        ObjectManager $objectManager,
        UserRepository $userRepository,
        CarRepository $carRepository,
        RuleRepository $ruleRepository,
        RideRepository $rideRepository,
        ReservationRepository $reservationRepository
    ): Response {
        $user = $security->getUser();
        $current_car = $carRepository->findOneBy(['owner' => $user]);
        $current_rules = $ruleRepository->findBy(['author' => $user]);
        $current_rides = $rideRepository->findBy(["driver" => $user]);
        if ($user != null && in_array('ROLE_USER', $user->getRoles())) {
            $form = $this->createForm(UserInformationType::class, $user);
            $form_car = $this->createForm(CarInformationType::class, $current_car);
            $form->handleRequest($request);
            $form_car->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) { // Check user update
                $userObj = $form->getData();
                $getUser = $userRepository->find($userObj->getId());
                $getUser->setEmail($userObj->getEmail())
                    ->setFirstName($userObj->getFirstName())
                    ->setLastName($userObj->getLastName())
                    ->setPhone($userObj->getPhone());
                $objectManager->persist($getUser);
                $objectManager->flush();
            } else if ($form_car->isSubmitted() && $form_car->isValid()) { // Check car update
                $car_brand = $form_car->get('brand')->getData();
                $car_model = $form_car->get('model')->getData();
                $car_seats = $form_car->get('seats')->getData();

                $current_car = $carRepository->findOneBy(['owner' => $user]);
                $current_car->setBrand($car_brand)
                    ->setModel($car_model)
                    ->setSeats($car_seats);
                $objectManager->persist($current_car);
                $objectManager->flush();
            }


            $reservations = [];

            $userRides = [];

            foreach ($rideRepository->findBy(["driver" => $user]) as $rid) {
                # code...
                $userRides[] = $rid;
            }
            
            foreach ($reservationRepository->findBy(["passenger" => $user]) as $res) {
                # code...
                $reservations[] = $res;
            }
            // foreach ($reservationRepository->findAll() as $res) {
            //     # code...
            //     foreach ($userRides as $rid) {
            //         # code...
            //         if($res->getRide() == $rid && $res->getPassenger() != $user){
            //             $reservations[] = $rid;
            //         }
            //     }
            // }
            foreach ($userRides as $rid) {
                # code...
                foreach ($rid->getReservations() as $r) {
                    # code...
                    $reservations[] = $r;
                }
            }
            
            

            usort($reservations, function($a, $b){
                return $b->getCreated() <=> $a->getCreated();
            });

            return $this->render('profile/index.html.twig', [
                'userObj' => $this->getUser(),
                'user' => $form->createView(),
                'form_car' => $form_car->createView(),
                'car' => $current_car,
                'rules' => $current_rules,
                'rides' => $current_rides,
                'reservations' => $reservations
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
}

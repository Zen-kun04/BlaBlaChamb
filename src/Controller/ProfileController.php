<?php

namespace App\Controller;

use App\Form\CarInformationType;
use App\Form\RuleInformationType;
use App\Form\UserInformationType;
use App\Repository\CarRepository;
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
        RuleRepository $ruleRepository
    ): Response {
        $user = $security->getUser();
        $current_car = $carRepository->findOneBy(['owner' => $user]);
        $current_rules = $ruleRepository->findBy(['author' => $user]);
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
            return $this->render('profile/index.html.twig', [
                'user' => $form->createView(),
                'form_car' => $form_car->createView(),
                'car' => $current_car,
                'rules' => $current_rules
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
}

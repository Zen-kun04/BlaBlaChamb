<?php

namespace App\Controller;

use App\Form\AnnouncementsFiltersType;
use App\Repository\RideRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/announcements')]
class AnnouncementsController extends AbstractController
{

    private RideRepository $rideRepository;

    public function __construct(RideRepository $rideRepository) {
        $this->rideRepository = $rideRepository;
    }

    #[Route('/', name: 'app_announcements')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(AnnouncementsFiltersType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $dep = $form->get("departure")->getData();
            $des = $form->get("destination")->getData();
            $date = $form->get("date")->getData();
            $creation = $form->get("creation")->getData();
            $builder = $this->rideRepository->createQueryBuilder('a');
            if($dep === "ASC") {
                $builder->addOrderBy("a.departure", "ASC");
            }else if($dep === "DESC") {
                $builder->addOrderBy("a.departure", "DESC");
            }
            if($des === "ASC") {
                $builder->addOrderBy("a.destination", "ASC");
            }else if($des === "DESC") {
                $builder->addOrderBy("a.destination", "DESC");
            }

            if($date === "ASC") {
                $builder->addOrderBy("a.date", "ASC");
            }else if($date === "DESC") {
                $builder->addOrderBy("a.date", "DESC");
            }

            if($creation === "ASC") {
                $builder->addOrderBy("a.created", "ASC");
            }else if($creation === "DESC") {
                $builder->addOrderBy("a.created", "DESC");
            }
            return $this->render('announcements/index.html.twig', [
                'filters' => $form->createView(),
                'results' => $builder->getQuery()->getResult()
            ]);
        }
        return $this->render('announcements/index.html.twig', [
            'filters' => $form->createView(),
            'results' => $this->rideRepository->createQueryBuilder('a')->getQuery()->getResult()
        ]);
    }
}

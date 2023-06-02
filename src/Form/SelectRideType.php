<?php

namespace App\Form;

use App\Entity\Ride;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectRideType extends AbstractType
{

    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManagerInterface, Security $security) {
        $this->entityManager = $entityManagerInterface;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $rideRepository = $this->entityManager->getRepository(Ride::class);
        $rides = $rideRepository->findBy(["driver" => $this->security->getUser()]);
        $user_rides = [];
        foreach ($rides as $index => $ride) {
            # code...
            $user_rides[$index + 1 . '. (' . $ride->getDate()->format('d/m/Y') . ')'] = $ride->getId();
        }
        $builder
            ->add('id', ChoiceType::class, [
                "label" => "Ride",
                "choices" => $user_rides
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}

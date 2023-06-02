<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\Ride;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            # code...
            $reservation = new Reservation();
            $reservation->setConfirmed($this->faker->boolean())
            ->setPassenger($this->getReference("user_" . $this->faker->numberBetween(0, 9)))
            ->setRide($this->getReference("ride_" . $this->faker->numberBetween(0, 9)))
            ->setCreated(new \DateTime());
            $manager->persist($reservation);
        }
        

        $manager->flush();
    }

    public function getDependencies(){
        return [
            RideFixtures::class,
            UserFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Ride;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RideFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            # code...
            $ride = new Ride();
            $ride->setCreated(new \DateTimeImmutable(date("Y-m-d H:i:s")))
            ->setDate(new \DateTimeImmutable(date("Y-m-d H:i:s")))
            ->setDeparture($this->faker->words(3, true))
            ->setDestination($this->faker->words(3, true))
            ->setDriver($this->getReference("user_" . $i))
            ->setSeats($this->getReference("car_" . $i)->getSeats())
            ->setPrice($this->faker->numberBetween(1, 100));
            $this->setReference("ride_" . $i, $ride);
            $manager->persist($ride);
        }
        

        $manager->flush();
    }

    public function getDependencies(){
        return [
            UserFixtures::class,
            RuleFixtures::class,
        ];
    }
}

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
            $ride->setCreated($this->faker->dateTimeBetween('-1 year', 'now'))
            ->setDate($this->faker->dateTimeBetween('-1 year', 'now'))
            ->setDeparture($this->faker->city())
            ->setDestination($this->faker->city())
            ->setDriver($this->getReference("user_" . $this->faker->numberBetween(0, 9)))
            ->setSeats($this->getReference("car_" . $this->faker->numberBetween(0, 49))->getSeats())
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

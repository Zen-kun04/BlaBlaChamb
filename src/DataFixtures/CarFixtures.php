<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            # code...
            $car = new Car();
            $car->setBrand($this->faker->name())
            ->setModel($this->faker->name())
            ->setSeats($this->faker->numberBetween(1, 4))
            ->setOwner($this->getReference("user_" . $i))
            ->setCreated(new \DateTimeImmutable(date('Y-m-d H:i:s')));
            $this->setReference("car_" . $i, $car);
            $manager->persist($car);
        }
        

        $manager->flush();
    }

    public function getDependencies() {
        return [
            UserFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail("donbaguettecorp@gmail.com")
        ->setFirstName("Don")
        ->setLastName("Baguette")
        ->setPhone("+33 6 66 66 66 66")
        ->setPassword($this->passwordHasher->hashPassword($user, "123456"))
        ->setCreated($this->faker->dateTimeBetween('-1 year', 'now'))
        ;
        $this->setReference('user_0', $user);
        $manager->persist($user);
        for ($i=1; $i < 10; $i++) { 
            $user = new User();
            $user->setEmail($this->faker->email())
            ->setFirstName($this->faker->firstName())
            ->setLastName($this->faker->lastName())
            ->setPhone($this->faker->phoneNumber())
            ->setPassword($this->passwordHasher->hashPassword($user, $this->faker->password()))
            ->setCreated($this->faker->dateTimeBetween('-1 year', 'now'))
            ;
            $this->setReference('user_' . $i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}

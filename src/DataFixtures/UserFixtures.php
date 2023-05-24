<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            # code...
            $user = new User();
            $user->setEmail($this->faker->email())
            ->setFirstName($this->faker->firstName())
            ->setLastName($this->faker->lastName())
            ->setPhone($this->faker->phoneNumber())
            ->setPassword($this->passwordHasher->hashPassword($user, "123456"))
            ->setCreated(new \DateTimeImmutable(date('Y-m-d H:i:s')));
            $this->addReference("user_" . $i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}

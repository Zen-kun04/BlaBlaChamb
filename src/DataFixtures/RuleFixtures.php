<?php

namespace App\DataFixtures;

use App\Entity\Rule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RuleFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            # code...
            $rule = new Rule();
            $rule->setAuthor($this->getReference("user_" . $this->faker->numberBetween(0, 9)))
            ->setName($this->faker->word());
            if($this->faker->numberBetween(0, 1) == 1) {
                $rule->setDescription($this->faker->paragraph(1));
            }
            $manager->persist($rule);
        }
        

        $manager->flush();
    }

    public function getDependencies(){
        return [
            UserFixtures::class,
        ];
    }
}

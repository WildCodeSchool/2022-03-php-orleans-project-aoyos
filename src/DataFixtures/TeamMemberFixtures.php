<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TeamMember;
use Faker\Factory;

class TeamMemberFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $numberMembers = 4;

        $faker = Factory::create();

        for ($i = 0; $i <= $numberMembers; $i++) {
            $teamMember = new TeamMember();

            $teamMember->setName($faker->name());
            $teamMember->setPosition($faker->words(2, true));
            $teamMember->setDescription($faker->sentences(2, true));

            $manager->persist($teamMember);
        }
        $manager->flush();
    }
}
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
            $imageName = 'member' . $i . '.webp';
            copy('https://picsum.photos/200/300?random=' . $i, 'public/uploads/images/team/' . $imageName);
            $teamMember->setPicture($imageName);

            $manager->persist($teamMember);
        }
        $manager->flush();
    }
}

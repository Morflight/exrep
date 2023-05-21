<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setFirstName('Admin')
            ->setLastName('John')
            ->setDateOfBirth(new DateTime('1990-01-01'))
            ->setEmail('admin@gmail.com')
            // Hashed and salted string for "root"
            ->setPassword('$2y$13$32QVwI6PMBY65XXH5vU3B.xgcVSvWGIsUtYUIa7T8PJqlf1/QsGPS');

        $manager->persist($user);
        $manager->flush();
    }
}

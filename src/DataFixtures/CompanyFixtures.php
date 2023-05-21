<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $companyNames = ['LVMH', 'Michelin', 'Bedrock', 'HR Teams', 'Nintendo'];

        foreach ($companyNames as $index => $companyName) {
            $company = (new Company)
                ->setName($companyName);

            $manager->persist($company);
            $this->addReference('company-' . $index, $company);
        }

        $manager->flush();
    }
}

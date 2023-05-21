<?php

namespace App\DataFixtures;

use App\Entity\ExpenseReport;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExpenseReportFixtures extends Fixture implements DependentFixtureInterface
{
    const FIXTURES_ANCHOR_DATE = '2023-05-15 12:00:00';

    public function load(ObjectManager $manager): void
    {
        for ($companyIndex = 0; $companyIndex < 4; $companyIndex++) {
            $company = $this->getReference('company-' . $companyIndex);

            for ($i = 0; $i < 25; $i++) {
                $amount = rand(0, 10000) / 100;
                $type = ExpenseReport::EXPENSE_TYPES[rand(0, 3)];
                $expenseDate = (new DateTime($this::FIXTURES_ANCHOR_DATE))->modify("-$i days");
                $registrationDate = $expenseDate->modify('+25 minutes');

                $expenseReport = (new ExpenseReport())
                    ->setAmount($amount)
                    ->setType($type)
                    ->setExpenseDate($expenseDate)
                    ->setRegistrationDate($registrationDate)
                    ->setCompany($company);

                $manager->persist($expenseReport);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
        ];
    }
}

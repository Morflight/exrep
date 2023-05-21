<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ExpenseReportRepository;
use App\Validator\IntegerWithTwoDecimalsConstraint;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpenseReportRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'expense:item']),
        new GetCollection(normalizationContext: ['groups' => 'expense:list']),
        new Post(
            normalizationContext: ['groups' => 'expense:item'],
            denormalizationContext: ['groups' => 'expense:create']
        ),
        new Put(
            normalizationContext: ['groups' => 'expense:item'],
            denormalizationContext: ['groups' => 'expense:create']
        ),
        new Delete(),
    ],
)]
class ExpenseReport
{
    const EXPENSE_TYPES = ['Gas Expense', 'Meal Expense', 'Toll Fees', 'Conference Expense'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['expense:list', 'expense:item'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['expense:list', 'expense:item', 'expense:create'])]
    #[Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date',
        ],
    )]
    private ?\DateTimeInterface $expenseDate = null;

    #[ORM\Column]
    #[Groups(['expense:list', 'expense:item', 'expense:create'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'number',
            'example' => 10.5,
        ],
    )]
    #[IntegerWithTwoDecimalsConstraint]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['expense:list', 'expense:item', 'expense:create'])]
    #[Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '2023-01-01 14:00:00',
        ],
    )]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\ManyToOne(inversedBy: 'expenseReports')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['expense:list', 'expense:item', 'expense:create'])]
    private ?Company $company = null;

    #[ORM\Column]
    #[Groups(['expense:list', 'expense:item', 'expense:create'])]
    #[Assert\Choice(self::EXPENSE_TYPES)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpenseDate(): ?\DateTimeInterface
    {
        return $this->expenseDate;
    }

    public function setExpenseDate(\DateTimeInterface $expenseDate): self
    {
        $this->expenseDate = $expenseDate;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}

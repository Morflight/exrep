<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'company:item']),
        new GetCollection(normalizationContext: ['groups' => 'company:list'])
    ],
    order: ['name' => 'DESC'],
    paginationEnabled: false,
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['company:list', 'expense:create'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['company:list', 'company:item', 'expense:list', 'expense:item'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: ExpenseReport::class)]
    private Collection $expenseReports;

    public function __construct()
    {
        $this->expenseReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ExpenseReport>
     */
    public function getExpenseReports(): Collection
    {
        return $this->expenseReports;
    }

    public function addExpenseReport(ExpenseReport $expenseReport): self
    {
        if (!$this->expenseReports->contains($expenseReport)) {
            $this->expenseReports->add($expenseReport);
            $expenseReport->setCompany($this);
        }

        return $this;
    }

    public function removeExpenseReport(ExpenseReport $expenseReport): self
    {
        if ($this->expenseReports->removeElement($expenseReport)) {
            // set the owning side to null (unless already changed)
            if ($expenseReport->getCompany() === $this) {
                $expenseReport->setCompany(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\TypePharmacyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePharmacyRepository::class)]
class TypePharmacy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Pharmacy::class)]
    private Collection $pharmacies;

    public function __construct()
    {
        $this->pharmacies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Pharmacy>
     */
    public function getPharmacies(): Collection
    {
        return $this->pharmacies;
    }

    public function addPharmacy(Pharmacy $pharmacy): static
    {
        if (!$this->pharmacies->contains($pharmacy)) {
            $this->pharmacies->add($pharmacy);
            $pharmacy->setType($this);
        }

        return $this;
    }

    public function removePharmacy(Pharmacy $pharmacy): static
    {
        if ($this->pharmacies->removeElement($pharmacy)) {
            // set the owning side to null (unless already changed)
            if ($pharmacy->getType() === $this) {
                $pharmacy->setType(null);
            }
        }

        return $this;
    }
}

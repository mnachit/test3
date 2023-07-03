<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Pharmacy::class)]
    private Collection $pharmacies;

    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Establishments::class)]
    private Collection $establishments;

    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Associations::class)]
    private Collection $associations;

    public function __construct()
    {
        $this->pharmacies = new ArrayCollection();
        $this->establishments = new ArrayCollection();
        $this->associations = new ArrayCollection();
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
            $pharmacy->setVille($this);
        }

        return $this;
    }

    public function removePharmacy(Pharmacy $pharmacy): static
    {
        if ($this->pharmacies->removeElement($pharmacy)) {
            // set the owning side to null (unless already changed)
            if ($pharmacy->getVille() === $this) {
                $pharmacy->setVille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Establishments>
     */
    public function getEstablishments(): Collection
    {
        return $this->establishments;
    }

    public function addEstablishment(Establishments $establishment): static
    {
        if (!$this->establishments->contains($establishment)) {
            $this->establishments->add($establishment);
            $establishment->setVille($this);
        }

        return $this;
    }

    public function removeEstablishment(Establishments $establishment): static
    {
        if ($this->establishments->removeElement($establishment)) {
            // set the owning side to null (unless already changed)
            if ($establishment->getVille() === $this) {
                $establishment->setVille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Associations>
     */
    public function getAssociations(): Collection
    {
        return $this->associations;
    }

    public function addAssociation(Associations $association): static
    {
        if (!$this->associations->contains($association)) {
            $this->associations->add($association);
            $association->setVille($this);
        }

        return $this;
    }

    public function removeAssociation(Associations $association): static
    {
        if ($this->associations->removeElement($association)) {
            // set the owning side to null (unless already changed)
            if ($association->getVille() === $this) {
                $association->setVille(null);
            }
        }

        return $this;
    }
}

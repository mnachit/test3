<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    private ?Pharmacy $Pharmacy = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    private ?Establishments $Etablissement = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    private ?Associations $Association = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    private ?CategoryCompte $category = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    private ?TypeCompte $type = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = "En attente";

    #[ORM\OneToMany(mappedBy: 'compte', targetEntity: Contact::class)]
    private Collection $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPharmacy(): ?Pharmacy
    {
        return $this->Pharmacy;
    }

    public function setPharmacy(?Pharmacy $Pharmacy): static
    {
        $this->Pharmacy = $Pharmacy;

        return $this;
    }

    public function getEtablissement(): ?Establishments
    {
        return $this->Etablissement;
    }

    public function setEtablissement(?Establishments $Etablissement): static
    {
        $this->Etablissement = $Etablissement;

        return $this;
    }

    public function getAssociation(): ?Associations
    {
        return $this->Association;
    }

    public function setAssociation(?Associations $Association): static
    {
        $this->Association = $Association;

        return $this;
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

    public function getCategory(): ?CategoryCompte
    {
        return $this->category;
    }

    public function setCategory(?CategoryCompte $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getType(): ?TypeCompte
    {
        return $this->type;
    }

    public function setType(?TypeCompte $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setCompte($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getCompte() === $this) {
                $contact->setCompte(null);
            }
        }

        return $this;
    }
}

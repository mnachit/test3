<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $compte = null;

    #[ORM\Column(length: 255)]
    private ?string $First = null;

    #[ORM\Column(length: 255)]
    private ?string $Last = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypePerson $Titre = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorycontacts $Category = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $portable = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;

    #[ORM\Column(length: 255)]
    private ?string $service = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): static
    {
        $this->compte = $compte;

        return $this;
    }

    public function getFirst(): ?string
    {
        return $this->First;
    }

    public function setFirst(string $First): static
    {
        $this->First = $First;

        return $this;
    }

    public function getLast(): ?string
    {
        return $this->Last;
    }

    public function setLast(string $Last): static
    {
        $this->Last = $Last;

        return $this;
    }

    public function getTitre(): ?TypePerson
    {
        return $this->Titre;
    }

    public function setTitre(?TypePerson $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getCategory(): ?Categorycontacts
    {
        return $this->Category;
    }

    public function setCategory(?Categorycontacts $Category): static
    {
        $this->Category = $Category;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPortable(): ?string
    {
        return $this->portable;
    }

    public function setPortable(string $portable): static
    {
        $this->portable = $portable;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): static
    {
        $this->service = $service;

        return $this;
    }
}

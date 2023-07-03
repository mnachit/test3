<?php

namespace App\Entity;

use App\Repository\PharmacyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PharmacyRepository::class)]
class Pharmacy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $portable = null;

    #[ORM\Column(length: 255)]
    private ?string $fax = null;

    #[ORM\Column(length: 255)]
    private ?string $site_internet = null;

    #[ORM\Column(length: 255)]
    private ?string $RC = null;

    #[ORM\Column(length: 255)]
    private ?string $INPE = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $Region = null;

    #[ORM\Column(length: 255)]
    private ?string $Code_postale = null;

    #[ORM\ManyToOne(inversedBy: 'pharmacies')]
    private ?Secteur $Secteur = null;

    #[ORM\ManyToOne(inversedBy: 'pharmacies')]
    private ?Ville $ville = null;

    #[ORM\ManyToOne(inversedBy: 'pharmacies')]
    private ?Pays $pays = null;

    #[ORM\ManyToOne(inversedBy: 'pharmacies')]
    private ?TypePharmacy $type = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateCreated = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateModified = null;

    #[ORM\Column(length: 255)]
    private ?string $ReasonForRejection = null;

    #[ORM\OneToMany(mappedBy: 'Pharmacy', targetEntity: Compte::class)]
    private Collection $comptes;

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

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

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(string $fax): static
    {
        $this->fax = $fax;

        return $this;
    }

    public function getSiteInternet(): ?string
    {
        return $this->site_internet;
    }

    public function setSiteInternet(string $site_internet): static
    {
        $this->site_internet = $site_internet;

        return $this;
    }

    public function getRC(): ?string
    {
        return $this->RC;
    }

    public function setRC(string $RC): static
    {
        $this->RC = $RC;

        return $this;
    }

    public function getINPE(): ?string
    {
        return $this->INPE;
    }

    public function setINPE(string $INPE): static
    {
        $this->INPE = $INPE;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->Region;
    }

    public function setRegion(string $Region): static
    {
        $this->Region = $Region;

        return $this;
    }

    public function getCodePostale(): ?string
    {
        return $this->Code_postale;
    }

    public function setCodePostale(string $Code_postale): static
    {
        $this->Code_postale = $Code_postale;

        return $this;
    }

    public function getSecteur(): ?Secteur
    {
        return $this->Secteur;
    }

    public function setSecteur(?Secteur $Secteur): static
    {
        $this->Secteur = $Secteur;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getType(): ?TypePharmacy
    {
        return $this->type;
    }

    public function setType(?TypePharmacy $type): static
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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->DateCreated;
    }

    public function setDateCreated(\DateTimeInterface $DateCreated): static
    {
        $this->DateCreated = $DateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->DateModified;
    }

    public function setDateModified(\DateTimeInterface $DateModified): static
    {
        $this->DateModified = $DateModified;

        return $this;
    }

    public function getReasonForRejection(): ?string
    {
        return $this->ReasonForRejection;
    }

    public function setReasonForRejection(string $ReasonForRejection): static
    {
        $this->ReasonForRejection = $ReasonForRejection;

        return $this;
    }

    /**
     * @return Collection<int, Compte>
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): static
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes->add($compte);
            $compte->setPharmacy($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): static
    {
        if ($this->comptes->removeElement($compte)) {
            // set the owning side to null (unless already changed)
            if ($compte->getPharmacy() === $this) {
                $compte->setPharmacy(null);
            }
        }

        return $this;
    }
}

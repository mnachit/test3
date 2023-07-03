<?php

namespace App\Entity;

use App\Repository\EstablishmentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstablishmentsRepository::class)]
class Establishments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'establishments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeEstablishments $type = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'establishments')]
    private ?SpecialityEstablishments $Speciality = null;

    #[ORM\Column(length: 255)]
    private ?string $portable = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $Site_internet = null;

    #[ORM\Column(length: 255)]
    private ?string $fax = null;

    #[ORM\Column(length: 255)]
    private ?string $RC = null;

    #[ORM\Column(length: 255)]
    private ?string $INPE = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    #[ORM\ManyToOne(inversedBy: 'establishments')]
    private ?Ville $ville = null;

    #[ORM\ManyToOne(inversedBy: 'establishments')]
    private ?Secteur $secteur = null;

    #[ORM\Column(length: 255)]
    private ?string $Region = null;

    #[ORM\Column(length: 255)]
    private ?string $Code_postale = null;

    #[ORM\ManyToOne(inversedBy: 'establishments')]
    private ?Pays $pays = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $ReasonForRejection = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateModified = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateCreated = null;

    #[ORM\OneToMany(mappedBy: 'Etablissement', targetEntity: Compte::class)]
    private Collection $comptes;

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeEstablishments
    {
        return $this->type;
    }

    public function setType(?TypeEstablishments $type): static
    {
        $this->type = $type;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSpeciality(): ?SpecialityEstablishments
    {
        return $this->Speciality;
    }

    public function setSpeciality(?SpecialityEstablishments $Speciality): static
    {
        $this->Speciality = $Speciality;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getSiteInternet(): ?string
    {
        return $this->Site_internet;
    }

    public function setSiteInternet(string $Site_internet): static
    {
        $this->Site_internet = $Site_internet;

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
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;

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

    public function getSecteur(): ?Secteur
    {
        return $this->secteur;
    }

    public function setSecteur(?Secteur $secteur): static
    {
        $this->secteur = $secteur;

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

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): static
    {
        $this->pays = $pays;

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

    public function getReasonForRejection(): ?string
    {
        return $this->ReasonForRejection;
    }

    public function setReasonForRejection(string $ReasonForRejection): static
    {
        $this->ReasonForRejection = $ReasonForRejection;

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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->DateCreated;
    }

    public function setDateCreated(\DateTimeInterface $DateCreated): static
    {
        $this->DateCreated = $DateCreated;

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
            $compte->setEtablissement($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): static
    {
        if ($this->comptes->removeElement($compte)) {
            // set the owning side to null (unless already changed)
            if ($compte->getEtablissement() === $this) {
                $compte->setEtablissement(null);
            }
        }

        return $this;
    }
}

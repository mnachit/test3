<?php

namespace App\Entity;

use App\Repository\PublicationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationsRepository::class)]
class Publications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ciblage $ciblage = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypePublication $Type = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EmplacementPublication $Emplacement = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    private ?TargetedPeople $People = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;

    #[ORM\Column(length: 255)]
    private ?string $Lien_Image = null;

    #[ORM\Column(length: 255)]
    private ?string $Lien_video = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Contenu = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $RaisonDeRejet = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_modified = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCiblage(): ?Ciblage
    {
        return $this->ciblage;
    }

    public function setCiblage(?Ciblage $ciblage): static
    {
        $this->ciblage = $ciblage;

        return $this;
    }

    public function getType(): ?TypePublication
    {
        return $this->Type;
    }

    public function setType(?TypePublication $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getEmplacement(): ?EmplacementPublication
    {
        return $this->Emplacement;
    }

    public function setEmplacement(?EmplacementPublication $Emplacement): static
    {
        $this->Emplacement = $Emplacement;

        return $this;
    }

    public function getPeople(): ?TargetedPeople
    {
        return $this->People;
    }

    public function setPeople(?TargetedPeople $People): static
    {
        $this->People = $People;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getLienImage(): ?string
    {
        return $this->Lien_Image;
    }

    public function setLienImage(string $Lien_Image): static
    {
        $this->Lien_Image = $Lien_Image;

        return $this;
    }

    public function getLienVideo(): ?string
    {
        return $this->Lien_video;
    }

    public function setLienVideo(string $Lien_video): static
    {
        $this->Lien_video = $Lien_video;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(string $Contenu): static
    {
        $this->Contenu = $Contenu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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

    public function getRaisonDeRejet(): ?string
    {
        return $this->RaisonDeRejet;
    }

    public function setRaisonDeRejet(string $RaisonDeRejet): static
    {
        $this->RaisonDeRejet = $RaisonDeRejet;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(\DateTimeInterface $date_modified): static
    {
        $this->date_modified = $date_modified;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code_barre = null;

    #[ORM\Column(length: 255)]
    private ?string $code_barre_2 = null;

    #[ORM\Column]
    private ?int $ppv = null;

    #[ORM\Column]
    private ?bool $need_prescription = null;

    #[ORM\Column]
    private ?bool $market_product = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $base_remboursement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_modified = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = "oui";

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategoryProduct $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FormeGalenique $forme_galenique = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Laboratory $laboratory = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gamme $gamme = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sousgame $sousgame = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?ClasseTherapeutique $classe_therapeutique = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TaxeAchat $taxe_achat = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TaxeVente $taxe_vente = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductTable $produit_tableau = null;

    #[ORM\Column(length: 255)]
    private ?string $pph = null;

    #[ORM\Column(length: 50)]
    private ?string $category_margin = "pp";

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dci $dci = null;

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

    public function getCodeBarre(): ?string
    {
        return $this->code_barre;
    }

    public function setCodeBarre(string $code_barre): static
    {
        $this->code_barre = $code_barre;

        return $this;
    }

    public function getCodeBarre2(): ?string
    {
        return $this->code_barre_2;
    }

    public function setCodeBarre2(string $code_barre_2): static
    {
        $this->code_barre_2 = $code_barre_2;

        return $this;
    }

    public function getPpv(): ?int
    {
        return $this->ppv;
    }

    public function setPpv(int $ppv): static
    {
        $this->ppv = $ppv;

        return $this;
    }

    public function isNeedPrescription(): ?bool
    {
        return $this->need_prescription;
    }

    public function setNeedPrescription(bool $need_prescription): static
    {
        $this->need_prescription = $need_prescription;

        return $this;
    }

    public function isMarketProduct(): ?bool
    {
        return $this->market_product;
    }

    public function setMarketProduct(bool $market_product): static
    {
        $this->market_product = $market_product;

        return $this;
    }

    public function getBaseRemboursement(): ?string
    {
        return $this->base_remboursement;
    }

    public function setBaseRemboursement(?string $base_remboursement): static
    {
        $this->base_remboursement = $base_remboursement;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(?\DateTimeInterface $date_created): static
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCategory(): ?CategoryProduct
    {
        return $this->category;
    }

    public function setCategory(?CategoryProduct $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getFormeGalenique(): ?FormeGalenique
    {
        return $this->forme_galenique;
    }

    public function setFormeGalenique(?FormeGalenique $forme_galenique): static
    {
        $this->forme_galenique = $forme_galenique;

        return $this;
    }

    public function getLaboratory(): ?Laboratory
    {
        return $this->laboratory;
    }

    public function setLaboratory(?Laboratory $laboratory): static
    {
        $this->laboratory = $laboratory;

        return $this;
    }

    public function getGamme(): ?Gamme
    {
        return $this->gamme;
    }

    public function setGamme(?Gamme $gamme): static
    {
        $this->gamme = $gamme;

        return $this;
    }

    public function getSousgame(): ?Sousgame
    {
        return $this->sousgame;
    }

    public function setSousgame(?Sousgame $sousgame): static
    {
        $this->sousgame = $sousgame;

        return $this;
    }

    public function getClasseTherapeutique(): ?ClasseTherapeutique
    {
        return $this->classe_therapeutique;
    }

    public function setClasseTherapeutique(?ClasseTherapeutique $classe_therapeutique): static
    {
        $this->classe_therapeutique = $classe_therapeutique;

        return $this;
    }

    public function getTaxeAchat(): ?TaxeAchat
    {
        return $this->taxe_achat;
    }

    public function setTaxeAchat(?TaxeAchat $taxe_achat): static
    {
        $this->taxe_achat = $taxe_achat;

        return $this;
    }

    public function getTaxeVente(): ?TaxeVente
    {
        return $this->taxe_vente;
    }

    public function setTaxeVente(?TaxeVente $taxe_vente): static
    {
        $this->taxe_vente = $taxe_vente;

        return $this;
    }

    public function getProduitTableau(): ?ProductTable
    {
        return $this->produit_tableau;
    }

    public function setProduitTableau(?ProductTable $produit_tableau): static
    {
        $this->produit_tableau = $produit_tableau;

        return $this;
    }

    public function getPph(): ?string
    {
        return $this->pph;
    }

    public function setPph(string $pph): static
    {
        $this->pph = $pph;

        return $this;
    }

    public function getCategoryMargin(): ?string
    {
        return $this->category_margin;
    }

    public function setCategoryMargin(string $category_margin): static
    {
        $this->category_margin = $category_margin;

        return $this;
    }

    public function getDci(): ?Dci
    {
        return $this->dci;
    }

    public function setDci(?Dci $dci): static
    {
        $this->dci = $dci;

        return $this;
    }
}

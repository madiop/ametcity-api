<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrestationsRepository")
 */
class Prestations
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="Le prix unitaire doit être renseigné")
     */
    private $prixUnitaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produits", inversedBy="prestations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\MaxDepth(2)
     * 
     * @Assert\NotBlank(message="Le produit doit être renseigné")
     */
    private $produit;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $specifications;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprises", inversedBy="prestations")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\MaxDepth(2)
     */
    private $entreprise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Professionnels", inversedBy="prestations")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\MaxDepth(2)
     */
    private $professionnel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneDevis", mappedBy="prestation")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\MaxDepth(2)
     */
    private $devis;

    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $tva;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->status = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getSpecifications(): ?string
    {
        return $this->specifications;
    }

    public function setSpecifications(?string $specifications): self
    {
        $this->specifications = $specifications;

        return $this;
    }

    public function getProduit(): ?Produits
    {
        return $this->produit;
    }

    public function setProduit(?Produits $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getEntreprises(): ?Entreprises
    {
        return $this->entreprise;
    }

    public function setEntreprises(?Entreprises $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getProfessionnel(): ?Professionnels
    {
        return $this->professionnel;
    }

    public function setProfessionnel(?Professionnels $professionnel): self
    {
        $this->professionnel = $professionnel;

        return $this;
    }

    /**
     * @return Collection|LigneDevis[]
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(LigneDevis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis[] = $devi;
            $devi->setPrestation($this);
        }

        return $this;
    }

    public function removeDevi(LigneDevis $devi): self
    {
        if ($this->devis->contains($devi)) {
            $this->devis->removeElement($devi);
            // set the owning side to null (unless already changed)
            if ($devi->getPrestation() === $this) {
                $devi->setPrestation(null);
            }
        }

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}

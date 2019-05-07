<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitsRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Produits
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
     * @ORM\Column(type="string", length=255)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="Le nom ne doit pas être vide")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories", inversedBy="produits", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\MaxDepth(2)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Prestations", mappedBy="produit")
     */
    private $prestations;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Prestations[]
     */
    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prestations $prestation): self
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations[] = $prestation;
            $prestation->setProduit($this);
        }

        return $this;
    }

    public function removePrestation(Prestations $prestation): self
    {
        if ($this->prestations->contains($prestation)) {
            $this->prestations->removeElement($prestation);
            // set the owning side to null (unless already changed)
            if ($prestation->getProduit() === $this) {
                $prestation->setProduit(null);
            }
        }

        return $this;
    }
}

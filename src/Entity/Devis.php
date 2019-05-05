<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DevisRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Devis
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="La date d'émission ne doit pas être vide")
     */
    private $dateEmission;

    /**
     * @ORM\Column(type="date")
     */
    private $dateExpiration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="devis")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="Le cclient doit être renseigné")
     * 
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneDevis", mappedBy="devis", orphanRemoval=true)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $lignes;

    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $totalHt;

    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $totalTva;

    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $totalTtc;

    public function __construct()
    {
        $this->lignes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmission(): ?\DateTimeInterface
    {
        return $this->dateEmission;
    }

    public function setDateEmission(\DateTimeInterface $dateEmission): self
    {
        $this->dateEmission = $dateEmission;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|LigneDevis[]
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(LigneDevis $ligne): self
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes[] = $ligne;
            $ligne->setDevis($this);
        }

        return $this;
    }

    public function removeLigne(LigneDevis $ligne): self
    {
        if ($this->lignes->contains($ligne)) {
            $this->lignes->removeElement($ligne);
            // set the owning side to null (unless already changed)
            if ($ligne->getDevis() === $this) {
                $ligne->setDevis(null);
            }
        }

        return $this;
    }

    public function getTotalHt(): ?float
    {
        return $this->totalHt;
    }

    public function setTotalHt(?float $totalHt): self
    {
        $this->totalHt = $totalHt;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->totalTtc;
    }

    public function setTotalTtc(?float $totalTtc): self
    {
        $this->totalTtc = $totalTtc;

        return $this;
    }

    public function getTotalTva(): ?float
    {
        return $this->totalTva;
    }

    public function setTotalTva(?float $totalTva): self
    {
        $this->totalTva = $totalTva;

        return $this;
    }
}

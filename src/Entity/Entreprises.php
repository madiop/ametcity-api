<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntreprisesRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Entreprises
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
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="La raison ne doit pas être vide")
     */
    private $raisonSociale;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $fax;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $siren;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $rcsVille;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $codeNaf;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $numeroTva;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Prestations", mappedBy="entreprises", cascade={"persist"})
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

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(string $raisonSociale): self
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getRcsVille(): ?string
    {
        return $this->rcsVille;
    }

    public function setRcsVille(?string $rcsVille): self
    {
        $this->rcsVille = $rcsVille;

        return $this;
    }

    public function getCodeNaf(): ?string
    {
        return $this->codeNaf;
    }

    public function setCodeNaf(?string $codeNaf): self
    {
        $this->codeNaf = $codeNaf;

        return $this;
    }

    public function getNumeroTva(): ?string
    {
        return $this->numeroTva;
    }

    public function setNumeroTva(?string $numeroTva): self
    {
        $this->numeroTva = $numeroTva;

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
            $prestation->setEntreprises($this);
        }

        return $this;
    }

    public function removePrestation(Prestations $prestation): self
    {
        if ($this->prestations->contains($prestation)) {
            $this->prestations->removeElement($prestation);
            // set the owning side to null (unless already changed)
            if ($prestation->getEntreprises() === $this) {
                $prestation->setEntreprises(null);
            }
        }

        return $this;
    }
}

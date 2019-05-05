<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialitesRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Specialites
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="Le libelle ne doit pas être vide")
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Professionels", inversedBy="specialites")
     */
    private $professionels;

    public function __construct()
    {
        $this->professionels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Professionels[]
     */
    public function getProfessionels(): Collection
    {
        return $this->professionels;
    }

    public function addProfessionel(Professionels $professionel): self
    {
        if (!$this->professionels->contains($professionel)) {
            $this->professionels[] = $professionel;
        }

        return $this;
    }

    public function removeProfessionel(Professionels $professionel): self
    {
        if ($this->professionels->contains($professionel)) {
            $this->professionels->removeElement($professionel);
        }

        return $this;
    }
}

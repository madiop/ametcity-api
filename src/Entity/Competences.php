<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompetencesRepository")
 */
class Competences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Professionels", inversedBy="competences")
     */
    private $professionnels;

    public function __construct()
    {
        $this->professionnels = new ArrayCollection();
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

    /**
     * @return Collection|Professionels[]
     */
    public function getProfessionnels(): Collection
    {
        return $this->professionnels;
    }

    public function addProfessionnel(Professionels $professionnel): self
    {
        if(is_null($this->professionnels)){
            $this->professionnels = new ArrayCollection();
        }
        if (!$this->professionnels->contains($professionnel)) {
            $this->professionnels[] = $professionnel;
        }

        return $this;
    }

    public function removeProfessionnel(Professionels $professionnel): self
    {
        if ($this->professionnels->contains($professionnel)) {
            $this->professionnels->removeElement($professionnel);
        }

        return $this;
    }
}

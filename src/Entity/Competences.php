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
     * @ORM\Column(type="string", length=100)
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
     * @return Collection|Professionel[]
     */
    public function getProfessionnels(): Collection
    {
        return $this->professionnels;
    }

    public function addProfessionnel(Professionel $professionnel): self
    {
        if (!$this->professionnels->contains($professionnel)) {
            $this->professionnels[] = $professionnel;
        }

        return $this;
    }

    public function removeProfessionnel(Professionel $professionnel): self
    {
        if ($this->professionnels->contains($professionnel)) {
            $this->professionnels->removeElement($professionnel);
        }

        return $this;
    }
}

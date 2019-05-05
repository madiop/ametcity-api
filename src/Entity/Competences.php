<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompetencesRepository")
 * 
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "app_competence_show",
 *         parameters = { "id" = "expr(object.getId())" }
 *     )
 * )
 * @Hateoas\Relation(
 *      "modify",
 *      href = @Hateoas\Route(
 *          "app_competence_update",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 * @Serializer\ExclusionPolicy("all")
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
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="Le nom ne doit pas être vide")
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Professionnels", inversedBy="competences")
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
     * @return Collection|Professionnels[]
     */
    public function getProfessionnels(): Collection
    {
        return $this->professionnels;
    }

    public function addProfessionnel(Professionnels $professionnel): self
    {
        if(is_null($this->professionnels)){
            $this->professionnels = new ArrayCollection();
        }
        if (!$this->professionnels->contains($professionnel)) {
            $this->professionnels[] = $professionnel;
        }

        return $this;
    }

    public function removeProfessionnel(Professionnels $professionnel): self
    {
        if(is_null($this->professionnels)){
            $this->professionnels = new ArrayCollection();
        }
        if ($this->professionnels->contains($professionnel)) {
            $this->professionnels->removeElement($professionnel);
        }

        return $this;
    }
}

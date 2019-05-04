<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfessionelsRepository")
 * @ORM\Table(name="professionels")
 * @Serializer\ExclusionPolicy("all")
 */
class Professionels
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $tauxHoraire;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="Le nombre d'année d'expérience ne doit pas être vide")
     */
    private $experience;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Competences", mappedBy="professionnels")
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $competences;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $user;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTauxHoraire()
    {
        return $this->tauxHoraire;
    }

    public function setTauxHoraire($tauxHoraire): self
    {
        $this->tauxHoraire = $tauxHoraire;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(?int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * @return Collection|Competences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->addProfessionnel($this);
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
            $competence->removeProfessionnel($this);
        }

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
}

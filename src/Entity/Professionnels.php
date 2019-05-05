<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfessionnelsRepository")
 * @ORM\Table(name="professionnels")
 * 
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "app_professionnel_show",
 *         parameters = { "id" = "expr(object.getId())" }
 *     )
 * )
 * @Hateoas\Relation(
 *      "modify",
 *      href = @Hateoas\Route(
 *          "app_professionnel_update",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_professionnel_delete",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 * @Hateoas\Relation(
 *     "user",
 *     embedded = @Hateoas\Embedded("expr(object.getUser())")
 * )
 * @Serializer\ExclusionPolicy("all")
 */
class Professionnels
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
    private $tjm;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Competences", mappedBy="professionnels", cascade={"persist"})
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Specialites", mappedBy="professionnels", cascade={"persist"})
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $specialites;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Prestations", mappedBy="professionnel")
     */
    private $prestations;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->specialites = new ArrayCollection();
        $this->prestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTjm()
    {
        return $this->tjm;
    }

    public function setTjm($tjm): self
    {
        $this->tjm = $tjm;

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
        if(is_null($this->competences)){
            $this->competences = new ArrayCollection();
        }
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->addProfessionnel($this);
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        if(is_null($this->competences)){
            $this->competences = new ArrayCollection();
        }
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

    /**
     * @return Collection|Specialites[]
     */
    public function getSpecialites(): Collection
    {
        return $this->specialites;
    }

    public function addSpecialite(Specialites $specialite): self
    {
        if(is_null($this->specialites)){
            $this->specialites = new ArrayCollection();
        }
        if (!$this->specialites->contains($specialite)) {
            $this->specialites[] = $specialite;
            $specialite->addProfessionnel($this);
        }

        return $this;
    }

    public function removeSpecialite(Specialites $specialite): self
    {
        if(is_null($this->specialites)){
            $this->specialites = new ArrayCollection();
        }
        if ($this->specialites->contains($specialite)) {
            $this->specialites->removeElement($specialite);
            $specialite->removeProfessionnel($this);
        }

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
            $prestation->setProfessionnel($this);
        }

        return $this;
    }

    public function removePrestation(Prestations $prestation): self
    {
        if ($this->prestations->contains($prestation)) {
            $this->prestations->removeElement($prestation);
            // set the owning side to null (unless already changed)
            if ($prestation->getProfessionnel() === $this) {
                $prestation->setProfessionnel(null);
            }
        }

        return $this;
    }
}

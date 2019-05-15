<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * 
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cet adresse email est déjà utilisée.")
 * @Serializer\ExclusionPolicy("all")
 */
class User implements AdvancedUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Serializer\Expose
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=500)
     * @Serializer\Expose
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, unique=true)
     * @Serializer\Expose
     * 
     * @Assert\Email(message="Cet adresse mail n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $confirmationToken;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=false, options={"default":false})
     * @Serializer\Expose
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Expose
     */
    private $dateInscription;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Expose
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $pays;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Expose
     */
    private $codePostale;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $twitter;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Devis", mappedBy="client")
     * @Serializer\Expose
     */
    private $devis;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Roles", mappedBy="users")
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\MaxDepth(1)
     */
    private $roles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Professionnels", cascade={"persist", "remove"})
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $professionnel;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Entreprises", cascade={"persist", "remove"})
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $entreprise;

    public function __construct($username)
    {
        $this->isActive = true;
        $this->username = $username;
        $this->devis = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function eraseCredentials()
    {
    }    
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->codePostale;
    }

    public function setCodePostale(?int $codePostale): self
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    /**
     * @return Collection|Devis[]
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if(is_null($this->devis)){
            $this->devis = new ArrayCollection();
        }
        if (!$this->devis->contains($devi)) {
            $this->devis[] = $devi;
            $devi->setClient($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if(is_null($this->devis)){
            $this->devis = new ArrayCollection();
        }
        if ($this->devis->contains($devi)) {
            $this->devis->removeElement($devi);
            // set the owning side to null (unless already changed)
            if ($devi->getClient() === $this) {
                $devi->setClient(null);
            }
        }

        return $this;
    }

    public function setRoles(?ArrayCollection $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(Roles $role): self
    {
        if(is_null($this->roles)){
            $this->roles = new ArrayCollection();
        }
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addUser($this);
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        if(is_null($this->roles)){
            $this->roles = new ArrayCollection();
        }
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeUser($this);
        }

        return $this;
    }

    public function getRoles()
    {
        if(is_null($this->roles)){
            return null;
        }
        $roles = [];
        foreach($this->roles as $role){
            $roles[] = $role->getName();
        }
        return $roles;
        // return $this->roles; // array('ROLE_USER');
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

    public function getEntreprise(): ?Entreprises
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprises $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }
}
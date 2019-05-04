<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

// * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
/**
 * 
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Serializer\Expose
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
     * @ORM\Column(type="boolean", nullable=true)
     * @Serializer\Expose
     */
    private $status;

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

    public function __construct($username)
    {
        $this->isActive = true;
        $this->username = $username;
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
    public function getRoles()
    {
        return array('ROLE_USER');
    }
    public function eraseCredentials()
    {
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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

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
}
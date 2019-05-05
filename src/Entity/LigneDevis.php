<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LigneDevisRepository")
 */
class LigneDevis
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="La quantité ne doit pas être vide")
     */
    private $quantite;

    /**
     * @ORM\Column(type="float")
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $montantHt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devis", inversedBy="lignes")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="Le devis doit être renseigné")
     */
    private $devis;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prestations", inversedBy="devis")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * 
     * @Assert\NotBlank(message="La prestations doit être renseignée")
     */
    private $prestation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getMontantHt(): ?float
    {
        return $this->montantHt;
    }

    public function setMontantHt(float $montantHt): self
    {
        $this->montantHt = $montantHt;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        return $this;
    }

    public function getPrestation(): ?Prestations
    {
        return $this->prestation;
    }

    public function setPrestation(?Prestations $prestation): self
    {
        $this->prestation = $prestation;

        return $this;
    }
}

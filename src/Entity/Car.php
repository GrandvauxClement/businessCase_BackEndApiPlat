<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $carburant;

    /**
     * @ORM\Column(type="integer")
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $kilometrage;

    /**
     * @ORM\Column(type="integer")
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $prix;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $dateAjout;

    /**
     * @ORM\ManyToOne(targetEntity=Garages::class, inversedBy="car")
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $garages;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="car")
     * @Groups("full")
     * @Groups({"read", "write"})
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getKilometrage(): ?string
    {
        return $this->kilometrage;
    }

    public function setKilometrage(string $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getGarages(): ?Garages
    {
        return $this->garages;
    }

    public function setGarages(?Garages $garages): self
    {
        $this->garages = $garages;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImages(Images $images): self
    {
        if (!$this->images->contains($images)) {
            $this->images[] = $images;
            $images->setCar($this);
        }

        return $this;
    }

    public function removeImages(Images $images): self
    {
        if ($this->images->contains($images)) {
            $this->images->removeElement($images);
            // set the owning side to null (unless already changed)
            if ($images->getCar() === $this) {
                $images->setCar(null);
            }
        }

        return $this;
    }
}

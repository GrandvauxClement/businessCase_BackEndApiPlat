<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GaragesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
 * @ORM\Entity(repositoryClass=GaragesRepository::class)
 */
class Garages
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
     * @Groups({"read", "write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $rue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $numTelephone;

    /**
     * @ORM\OneToMany(targetEntity=Car::class, mappedBy="garages")
     */
    private $car;

    /**
     * @ORM\ManyToOne(targetEntity=Pro::class, inversedBy="garages")
     */
    private $pro;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $numSiret;

    /**
     * @return mixed
     */
    public function getNumSiret()
    {
        return $this->numSiret;
    }

    /**
     * @param mixed $numSiret
     */
    public function setNumSiret($numSiret): void
    {
        $this->numSiret = $numSiret;
    }

    public function __construct()
    {
        $this->car = new ArrayCollection();
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getNumTelephone(): ?string
    {
        return $this->numTelephone;
    }

    public function setNumTelephone(string $numTelephone): self
    {
        $this->numTelephone = $numTelephone;

        return $this;
    }

    /**
     * @return Collection|Car[]
     */
    public function getCar(): Collection
    {
        return $this->car;
    }

    public function addCar(Car $car): self
    {
        if (!$this->car->contains($car)) {
            $this->car[] = $car;
            $car->setGarages($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->car->contains($car)) {
            $this->car->removeElement($car);
            // set the owning side to null (unless already changed)
            if ($car->getGarages() === $this) {
                $car->setGarages(null);
            }
        }

        return $this;
    }

    public function getPro(): ?Pro
    {
        return $this->pro;
    }

    public function setPro(?Pro $pro): self
    {
        $this->pro = $pro;

        return $this;
    }
}

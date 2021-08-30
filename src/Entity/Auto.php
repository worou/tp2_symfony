<?php

namespace App\Entity;

use App\Repository\AutoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AutoRepository::class)
 */
class Auto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     * message = "Le champ '{{ label }}' n'est pas {{ value }}!")
     * @Assert\Length(
     *      min = 2,
     *      max = 12,
     *      minMessage = "Le champ marque doit contenir au moins {{ limit }} charactères.",
     *      maxMessage = "Le champ marque doit contenir au plus {{ limit }} charactères.")
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     * message = "Le champ '{{ label }}' n'est pas {{ value }}!")
     */
    private $modele;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     * *@Assert\Range(
     *      min = 120,
     *      max = 600,
     *      notInRangeMessage = "La puissance doit être comprise entre {{ min }}ch et {{ max }}ch."
     * )
     */
    private $puissance;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(
     * message = "Le champ '{{ label }}' n'est pas {{ value }}!")
     * * @Assert\LessThan(
     *     value = 1000000
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     * message = "Le champ '{{ label }}' n'est pas {{ value }}!")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]{2,6}$/",
     *     match=true,
     *     message="Ce champ doit contenir de 2 à 5 caractères")
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="autos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

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

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): self
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}

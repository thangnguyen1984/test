<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Category::class)]
    private Collection $categories;

    #[ORM\Column(length: 12)]
    private ?string $title = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $eId = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getEId(): ?int
    {
        return $this->eId;
    }

    public function setEId(?int $eId): self
    {
        $this->eId = $eId;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // $metadata->addPropertyConstraint('title', new Assert\Length(array(
        //     'min'   => 3,
        //     'minMessage' => 'Your name must have at least {{ min }} characters.',
        // )));

        $metadata->addPropertyConstraint('price', new Assert\Range([
            'min' => 0,
            'max' => 200,
            'notInRangeMessage' => 'Price must be between {{ min }} and {{ max }} to enter',
        ]));
    }
}

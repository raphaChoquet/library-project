<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\LibraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=LibraryRepository::class)
 * @UniqueEntity("name")
 */
#[
ApiResource(
    collectionOperations: [
        'get' => ['method' => 'GET'],
        'post' => ['method' => 'POST'],
    ],
    itemOperations: [
        'get' => ['method' => 'GET',],
        'put' => [
            'method' => 'PUT',
            'security' => "object.createBy == user",
        ],
        'delete' => [
            'method' => 'DELETE',
            'security' => "object.createBy == user",
        ],
    ],
    denormalizationContext: ['groups' =>  ['library', 'library:write']],
    normalizationContext: ['groups' =>  ['library', 'library:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'description' => 'partial', 'createBy'=> 'exact'])]
#[ApiFilter(
    OrderFilter::class,
    properties: ['name', 'createdAt', 'updatedAt'],
    arguments: ['orderParameterName' => 'order']
)]
class Library
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"library"})
     * @Assert\NotBlank()
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"library"})
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="libraries")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Blameable(on="create")
     * @Groups({"library:read"})
     */
    private ?User $createBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"library:read"})
     */
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable()
     * @Groups({"library:read"})
     */
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="library", orphanRemoval=true)
     */
    #[ApiSubresource]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreateBy(): ?User
    {
        return $this->createBy;
    }

    public function setCreateBy(?User $createBy): self
    {
        $this->createBy = $createBy;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setLibrary($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getLibrary() === $this) {
                $book->setLibrary(null);
            }
        }

        return $this;
    }
}

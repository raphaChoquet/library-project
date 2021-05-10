<?php
declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @UniqueEntity(
 *     fields={"library", "title"},
 *     errorPath="title",
 *     message="This title is already in this library."
 * )
 */
#[
ApiResource(
    collectionOperations: [
        'get' => ['method' => 'GET'],
        'post' => [
            'method' => 'POST',
            'denormalization_context' => ['groups' => ['book', 'book:write', 'book:create']],
        ]
    ],
    itemOperations: [
        'get' => ['method' => 'GET'],
        'put' => [
            'method' => 'PUT',
            'security' => "object.library.createBy == user",
        ],
        'delete' => [
            'method' => 'DELETE',
            'security' => "object.library.createBy == user",
        ]
    ],
    denormalizationContext: ['groups' =>  ['book', 'book:write']],
    normalizationContext: ['groups' =>  ['book', 'book:read']]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: ['title' => 'partial', 'author' => 'partial', 'summary' => 'partial', 'library'=> 'exact']
)]
#[ApiFilter(
    OrderFilter::class,
    properties: ['title', 'author', 'createdAt', 'updatedAt'],
    arguments: ['orderParameterName' => 'order']
)]
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"book"})
     * @Assert\NotBlank()
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"book"})
     */
    private ?string $summary = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"book"})
     * @Assert\NotBlank()
     */
    private ?string $author = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"book"})
     */
    private ?int $nbrOfPages = null;

    /**
     * @ORM\ManyToOne(targetEntity=Library::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"book:create"})
     * @Assert\NotBlank()
     * @CustomAssert\MyLibrary()
     */
    private ?Library $library = null;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"book:read"})
     */
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable()
     * @Groups({"book:read"})
     */
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getNbrOfPages(): ?int
    {
        return $this->nbrOfPages;
    }

    public function setNbrOfPages(?int $nbrOfPages): self
    {
        $this->nbrOfPages = $nbrOfPages;

        return $this;
    }

    public function getLibrary(): ?Library
    {
        return $this->library;
    }

    public function setLibrary(?Library $library): self
    {
        $this->library = $library;

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
}

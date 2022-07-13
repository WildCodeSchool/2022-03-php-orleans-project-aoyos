<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use App\Model\Localizable;
use Doctrine\ORM\Mapping as ORM;
use App\Config\ReservationStatus;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[Vich\Uploadable]
class Reservation implements Localizable
{
    public const MAX_INT_NUMBER = 2147483647;
    public const MIN_ATTENDEES = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(groups: ['clientInfos'])]
    #[Assert\Length(
        max: 255,
        groups: ['clientInfos']
    )]
    private string $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(groups: ['clientInfos'])]
    #[Assert\Length(
        max: 255,
        groups: ['clientInfos']
    )]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(groups: ['clientInfos'])]
    #[Assert\Length(
        max: 255,
        groups: ['clientInfos']
    )]
    private string $company;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(groups: ['clientInfos'])]
    #[Assert\Length(
        max: 255,
        groups: ['clientInfos']
    )]
    #[Assert\Email(groups: ['clientInfos'])]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(groups: ['clientInfos'])]
    #[Assert\Length(
        max: 255,
        groups: ['clientInfos']
    )]
    private string $phone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(groups: ['eventInfos'])]
    #[Assert\Length(
        max: 255,
        groups: ['eventInfos']
    )]
    private string $formula;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(groups: ['eventInfos'])]
    #[Assert\Length(
        max: 255,
        groups: ['eventInfos']
    )]
    private string $eventType;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(groups: ['eventInfos'])]
    #[Assert\Length(
        max: 255,
        groups: ['eventInfos']
    )]
    private string $address;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(groups: ['eventInfos'])]
    #[Assert\LessThan(
        propertyPath: 'dateEnd',
        groups: ['eventInfos']
    )]
    private DateTimeInterface $dateStart;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(groups: ['eventInfos'])]
    #[Assert\GreaterThan(
        propertyPath: 'dateStart',
        groups: ['eventInfos']
    )]
    private DateTimeInterface $dateEnd;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(groups: ['eventInfos'])]
    #[Assert\Range(
        min: self::MIN_ATTENDEES,
        max: self::MAX_INT_NUMBER,
        groups: ['eventInfos']
    )]
    private int $attendees;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'reservations')]
    private ?Artist $artist = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\Positive]
    private ?float $price;

    #[ORM\ManyToMany(targetEntity: MusicalStyle::class, inversedBy: 'reservations')]
    private Collection $musicalStyles;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentClient;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentAdmin;

    #[ORM\Column(type: 'float', nullable: true)]
    private float $longitude;

    #[ORM\Column(type: 'float', nullable: true)]
    private float $latitude;

    private float $distance = 0;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        max: 255
    )]
    private ?string $bill = null;

    #[Vich\UploadableField(mapping: 'bills', fileNameProperty: 'bill')]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'application/pdf', 'application/x-pdf' ]
    )]
    private ?File $billFile = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->musicalStyles = new ArrayCollection();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFormula(): ?string
    {
        return $this->formula;
    }

    public function setFormula(string $formula): self
    {
        $this->formula = $formula;

        return $this;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getAttendees(): ?int
    {
        return $this->attendees;
    }

    public function setAttendees(int $attendees): self
    {
        $this->attendees = $attendees;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, MusicalStyle>
     */
    public function getMusicalStyles(): ?Collection
    {
        return $this->musicalStyles;
    }

    public function addMusicalStyle(MusicalStyle $musicalStyle): self
    {
        if (!$this->musicalStyles->contains($musicalStyle)) {
            $this->musicalStyles[] = $musicalStyle;
        }

        return $this;
    }

    public function removeMusicalStyle(MusicalStyle $musicalStyle): self
    {
        $this->musicalStyles->removeElement($musicalStyle);

        return $this;
    }

    public function getCommentClient(): ?string
    {
        return $this->commentClient;
    }

    public function setCommentClient(?string $commentClient): self
    {
        $this->commentClient = $commentClient;

        return $this;
    }

    public function getCommentAdmin(): ?string
    {
        return $this->commentAdmin;
    }

    public function setCommentAdmin(?string $commentAdmin): self
    {
        $this->commentAdmin = $commentAdmin;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getBill(): ?string
    {
        return $this->bill;
    }

    public function setBill(?string $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    public function setBillFile(?File $billFile = null): void
    {
        $this->billFile = $billFile;

        if (null !== $billFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getBillFile(): ?File
    {
        return $this->billFile;
    }
}

<?php

namespace App\Entity;

use App\Config\ReservationStatus;
use App\Repository\ReservationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
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
    #[Assert\Length(
        max: 255,
        groups: ['eventInfos']
    )]
    private int $attendees;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'reservations')]
    private ?Artist $artist;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
}

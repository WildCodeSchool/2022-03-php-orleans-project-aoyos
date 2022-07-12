<?php

namespace App\Entity;

use App\Model\Localizable;
use App\Repository\ArtistRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** * @SuppressWarnings(PHPMD.ExcessiveClassComplexity) */
#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist implements Localizable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max: 255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max: 255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private string $lastname;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private DateTime $birthdate;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max: 255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private string $phone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max: 255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    #[Assert\Email(groups: ['djInfos'])]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max: 255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private string $address;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max: 255, groups: ['djProfile'])]
    #[Assert\NotBlank(groups: ['djProfile'])]
    private string $artistName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max: 255, groups: ['djProfile'])]
    #[Assert\NotBlank(groups: ['djProfile'])]
    private string $equipment;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255, groups: ['djProfile'])]
    private string $message;

    #[ORM\ManyToMany(targetEntity: MusicalStyle::class, inversedBy: 'artists', cascade: ['persist'])]
    #[Assert\Count(
        min: 1,
        groups: ['djProfile'],
        minMessage: 'Merci de choisir au moins {{ limit }} genre musical'
    )]
    private Collection $musicalStyles;

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToOne(inversedBy: 'artist', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $longitude;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $latitude;

    #[ORM\OneToOne(inversedBy: 'artist', targetEntity: Document::class, cascade: ['persist', 'remove'])]
    private ?Document $documents = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $instagram;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $soundCloud;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $facebook;

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: Unavailability::class, orphanRemoval: true)]
    private Collection $unavailabilities;


    public function __construct()
    {
        $this->musicalStyles = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->unavailabilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTime $birthdate): self
    {
        $this->birthdate = $birthdate;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getArtistName(): ?string
    {
        return $this->artistName;
    }

    public function setArtistName(string $artistName): self
    {
        $this->artistName = $artistName;

        return $this;
    }

    public function getEquipment(): ?string
    {
        return $this->equipment;
    }

    public function setEquipment(string $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection<int, MusicalStyle>
     */
    public function getMusicalStyles(): Collection
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

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setArtist($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getArtist() === $this) {
                $reservation->setArtist(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getDocuments(): ?Document
    {
        return $this->documents;
    }

    public function setDocuments(?Document $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getSoundCloud(): ?string
    {
        return $this->soundCloud;
    }

    public function setSoundCloud(?string $soundCloud): self
    {
        $this->soundCloud = $soundCloud;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * @return Collection<int, Unavailability>
     */
    public function getUnavailabilities(): Collection
    {
        return $this->unavailabilities;
    }

    public function addUnavailability(Unavailability $unavailability): self
    {
        if (!$this->unavailabilities->contains($unavailability)) {
            $this->unavailabilities[] = $unavailability;
            $unavailability->setArtist($this);
        }

        return $this;
    }

    public function removeUnavailability(Unavailability $unavailability): self
    {
        if ($this->unavailabilities->removeElement($unavailability)) {
            // set the owning side to null (unless already changed)
            if ($unavailability->getArtist() === $this) {
                $unavailability->setArtist(null);
            }
        }

        return $this;
    }
}

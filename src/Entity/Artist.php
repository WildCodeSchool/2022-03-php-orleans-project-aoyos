<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max:255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max:255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private string $lastname;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private DateTime $birthdate;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max:255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private string $phone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max:255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    #[Assert\Email(groups: ['djInfos'])]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max:255, groups: ['djInfos'])]
    #[Assert\NotBlank(groups: ['djInfos'])]
    private string $address;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max:255, groups: ['djProfile'])]
    #[Assert\NotBlank(groups: ['djProfile'])]
    private string $artistName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max:255, groups: ['djProfile'])]
    #[Assert\NotBlank(groups: ['djProfile'])]
    private string $equipment;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max:255, groups: ['djProfile'])]
    #[Assert\NotBlank(groups: ['djProfile'])]
    private string $message;

    #[ORM\ManyToMany(targetEntity: MusicalStyle::class, inversedBy: 'artists', cascade:['persist'])]
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

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $siretNumber = null;

    public function __construct()
    {
        $this->musicalStyles = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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

    public function getSiretNumber(): ?string
    {
        return $this->siretNumber;
    }

    public function setSiretNumber(?string $siretNumber): self
    {
        $this->siretNumber = $siretNumber;

        return $this;
    }
}

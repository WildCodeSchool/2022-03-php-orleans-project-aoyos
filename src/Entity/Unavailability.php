<?php

namespace App\Entity;

use App\Repository\UnavailabilityRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UnavailabilityRepository::class)]
class Unavailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'unavailabilities')]
    #[ORM\JoinColumn(nullable: false)]
    private Artist $artist;

    #[ORM\Column(type: 'date')]
    #[Assert\LessThanOrEqual(
        propertyPath: 'dateEnd'
    )]
    #[Assert\GreaterThanOrEqual('today')]
    private DateTimeInterface $dateStart;

    #[ORM\Column(type: 'date')]
    #[Assert\GreaterThanOrEqual(
        propertyPath: 'dateStart'
    )]
    private DateTimeInterface $dateEnd;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateStart(): ?DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CoordinateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoordinateRepository::class)]
class Coordinate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'float')]
    private float $coordX;

    #[ORM\Column(type: 'float')]
    private float $coordY;

    #[ORM\OneToOne(mappedBy: 'coordinates', targetEntity: Artist::class, cascade: ['persist', 'remove'])]
    private Artist $artist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoordX(): ?float
    {
        return $this->coordX;
    }

    public function setCoordX(float $coordX): self
    {
        $this->coordX = $coordX;

        return $this;
    }

    public function getCoordY(): ?float
    {
        return $this->coordY;
    }

    public function setCoordY(float $coordY): self
    {
        $this->coordY = $coordY;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        // unset the owning side of the relation if necessary
        if ($artist === null && $this->artist !== null) {
            $this->artist->setCoordinates(null);
        }

        // set the owning side of the relation if necessary
        if ($artist !== null && $artist->getCoordinates() !== $this) {
            $artist->setCoordinates($this);
        }

        $this->artist = $artist;

        return $this;
    }
}

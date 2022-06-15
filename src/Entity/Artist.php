<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use DateTime;
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
}

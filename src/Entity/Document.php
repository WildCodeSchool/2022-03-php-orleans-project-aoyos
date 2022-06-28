<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[Vich\Uploadable]
class Document implements Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $identityCard = null;

    #[Vich\UploadableField(mapping: 'dj_documents', fileNameProperty: 'identityCard')]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'application/pdf', 'application/x-pdf' ]
    )]
    private ?File $identityCardFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $identityPhoto = null;

    #[Vich\UploadableField(mapping: 'dj_documents', fileNameProperty: 'identityPhoto')]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'application/pdf', 'application/x-pdf' ]
    )]
    private ?File $identityPhotoFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $kbis = null;

    #[Vich\UploadableField(mapping: 'dj_documents', fileNameProperty: 'kbis')]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'application/pdf', 'application/x-pdf' ]
    )]
    private ?File $kbisFile = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'documents', targetEntity: Artist::class, cascade: ['persist', 'remove'])]
    private Artist $artist;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $siretNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentityCard(): ?string
    {
        return $this->identityCard;
    }

    public function setIdentityCard(?string $identityCard): self
    {
        $this->identityCard = $identityCard;

        return $this;
    }

    public function setIdentityCardFile(?File $identityCardFile = null): void
    {
        $this->identityCardFile = $identityCardFile;

        if (null !== $identityCardFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getIdentityCardFile(): ?File
    {
        return $this->identityCardFile;
    }

    public function getIdentityPhoto(): ?string
    {
        return $this->identityPhoto;
    }

    public function setIdentityPhoto(?string $identityPhoto): self
    {
        $this->identityPhoto = $identityPhoto;

        return $this;
    }

    public function setIdentityPhotoFile(?File $identityPhotoFile = null): void
    {
        $this->identityPhotoFile = $identityPhotoFile;

        if (null !== $identityPhotoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getIdentityPhotoFile(): ?File
    {
        return $this->identityPhotoFile;
    }

    public function getKbis(): ?string
    {
        return $this->kbis;
    }

    public function setKbis(?string $kbis): self
    {
        $this->kbis = $kbis;

        return $this;
    }

    public function setKbisFile(?File $kbisFile = null): void
    {
        $this->kbisFile = $kbisFile;

        if (null !== $kbisFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getKbisFile(): ?File
    {
        return $this->kbisFile;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        // unset the owning side of the relation if necessary
        if ($artist === null && $this->artist !== null) {
            $this->artist->setDocuments(null);
        }

        // set the owning side of the relation if necessary
        if ($artist !== null && $artist->getDocuments() !== $this) {
            $artist->setDocuments($this);
        }

        $this->artist = $artist;

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

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
              $this->id,
              $this->identityCard,
              $this->identityPhoto,
              $this->kbis
          ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
              $this->id,
          ) = unserialize($serialized);
    }
}

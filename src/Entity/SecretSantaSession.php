<?php

# src/Entity/SecretSantaSession.php
namespace App\Entity;

use App\Repository\SecretSantaSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecretSantaSessionRepository::class)]
class SecretSantaSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $code = null;

    #[ORM\Column]
    private bool $isDrawn = false;

    #[ORM\OneToMany(targetEntity: Participant::class, mappedBy: 'session', cascade: ['persist'], orphanRemoval: true)]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function isDrawn(): bool
    {
        return $this->isDrawn;
    }

    public function setIsDrawn(bool $isDrawn): self
    {
        $this->isDrawn = $isDrawn;
        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setSession($this);
        }
        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            if ($participant->getSession() === $this) {
                $participant->setSession(null);
            }
        }
        return $this;
    }
}

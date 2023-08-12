<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: GameParticipant::class)]
    private Collection $gameParticipants;

    public function __construct()
    {
        $this->gameParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, GameParticipant>
     */
    public function getGameParticipants(): Collection
    {
        return $this->gameParticipants;
    }

    public function addGameParticipant(GameParticipant $gameParticipant): static
    {
        if (!$this->gameParticipants->contains($gameParticipant)) {
            $this->gameParticipants->add($gameParticipant);
            $gameParticipant->setPlayer($this);
        }

        return $this;
    }

    public function removeGameParticipant(GameParticipant $gameParticipant): static
    {
        if ($this->gameParticipants->removeElement($gameParticipant)) {
            // set the owning side to null (unless already changed)
            if ($gameParticipant->getPlayer() === $this) {
                $gameParticipant->setPlayer(null);
            }
        }

        return $this;
    }
}

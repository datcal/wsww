<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\GameController;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    operations:
        [
            new Get(),
            new Get(name: 'join', uriTemplate: '/games/{id}/join', controller: GameController::class),
            new Post(),
            new Delete(),
            new GetCollection()
        ]
)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $create_at = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: GameParticipant::class)]
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

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeImmutable $create_at): static
    {
        $this->create_at = $create_at;

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
            $gameParticipant->setGame($this);
        }

        return $this;
    }

    public function removeGameParticipant(GameParticipant $gameParticipant): static
    {
        if ($this->gameParticipants->removeElement($gameParticipant)) {
            // set the owning side to null (unless already changed)
            if ($gameParticipant->getGame() === $this) {
                $gameParticipant->setGame(null);
            }
        }

        return $this;
    }
}

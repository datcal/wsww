<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Action\CreateGameAction;
use App\Controller\GameController;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    operations:
        [
            new Get(),
            new Get(uriTemplate: '/games/{id}/join', controller: GameController::class, name: 'join'),
            new Post(controller: CreateGameAction::class, name: 'create'),
            new Delete(),
            new GetCollection()
        ],
    normalizationContext: ['groups' => ['game']]
)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('game')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('game')]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $create_at = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: GameParticipant::class)]
    #[Groups('game')]
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

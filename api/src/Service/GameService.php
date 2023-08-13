<?php
namespace App\Service;
use App\Entity\Game;
use App\Entity\GameParticipant;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
class GameService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function joinGame(Game $game, Player $player): void
    {
        $gameParticipant = new GameParticipant();
        $gameParticipant->setGame($game);
        $gameParticipant->setPlayer($player);

        $this->entityManager->persist($gameParticipant);
        $this->entityManager->flush();
    }
}

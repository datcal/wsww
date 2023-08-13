<?php
namespace App\Action;

use App\Entity\Game;
use App\Repository\PlayerRepository;
use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CreateGameAction extends AbstractController
{

    private GameService $gameService;
    private PlayerRepository $playerRepository;

    public function __construct(GameService $gameService, PlayerRepository $playerRepository)
    {

        $this->gameService = $gameService;
        $this->playerRepository = $playerRepository;
    }
    public function __invoke(Game $game): Game
    {
        $player = $this->playerRepository->find(7);
        $this->gameService->joinGame($game, $player);
        return $game;
    }
}

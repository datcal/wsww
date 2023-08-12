<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GameParticipant;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;

class GameController extends AbstractController
{
    private GameRepository $gameRepository;
    private PlayerRepository $playerRepository;

    public function __construct(GameRepository $gameRepository, PlayerRepository $playerRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->playerRepository = $playerRepository;
    }

    #[Route('/games/{id}/join', name: 'join', methods: ['GET'])]
    public function join(Request $request): Response
    {
        $game = $this->gameRepository->find($request->get('id'));
        $player = $this->playerRepository->find(4);

        $gameParticipant = new GameParticipant();
        $gameParticipant->setGame($game);
        $gameParticipant->setPlayer($player);


        return $this->json($gameParticipant);
    }
}

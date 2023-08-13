<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
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
    private EntityManagerInterface $entityManager;

    public function __construct(GameRepository $gameRepository, PlayerRepository $playerRepository, EntityManagerInterface $entityManager)
    {
        $this->gameRepository = $gameRepository;
        $this->playerRepository = $playerRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/games/{id}/join', name: 'join', methods: ['GET'])]
    public function join(Request $request, Game $game): Response
    {
        $player = $this->playerRepository->find(7);

        $gameParticipant = new GameParticipant();
        $gameParticipant->setGame($game);
        $gameParticipant->setPlayer($player);

        $this->entityManager->persist($gameParticipant);
        $this->entityManager->flush();

        return $this->json(array("status" => "ok"));
    }
/*
    #[NoReturn]
    #[Route('/games', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        dd($request);
        $game = new Game();
        $game->setName($request->request->get('name'));
        $game->setCreateAt($request->request->get('createAt'));
        $this->entityManager->persist($game);

        $player = $this->playerRepository->find(7);

        $gameParticipant = new GameParticipant();
        $gameParticipant->setGame($game);
        $gameParticipant->setPlayer($player);

        $this->entityManager->persist($gameParticipant);
        $this->entityManager->flush();

        return $this->json(array("status" => "ok"));
    }*/
}

<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use App\Entity\Category;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Suspension;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="games")
     */
    public function players(ManagerRegistry $doctrine): Response
    {
        $games = $doctrine->getRepository(Game::class)->findBy([], ["category" => 'DESC', "gameDate" => 'DESC' ]);
        return  $this->render("admin/games.html.twig", [
            "games" => $games
        ]);
    }

    /**
     * @Route("/admin/manageGame/{id}", name="manageGame")
     */
    public function game(ManagerRegistry $doctrine, $id): Response
    {
        $game = $doctrine->getRepository(Game::class)->findOneBy(array("id"=>$id));
        $playersSuspended = $this->getSuspendedPlayer($game->getGameDate(), $game->getCategory(), $doctrine, $game);
        if( count($_POST ) != 0 ){
            $this->removeAllPlayer($game, $doctrine);
            foreach ($_POST as $id){
                $player = $doctrine->getRepository(Player::class)->findOneBy(['id' => $id]);
                if(!in_array($player, $playersSuspended)){
                    $game->addPlayer($player);
                }
            }
            $em = $doctrine->getManager();
            $em->persist($game);
            $em->flush();

            return $this->redirectToRoute('players');
        }

        $players = $game->getPlayers();
        $idPlayers = [];
        foreach ($players as $player){
            $idPlayers[]=$player->getId();
        }
        $playersList = $doctrine->getRepository(Player::class)->findAll();
        $playerSelectionnable = [];
        foreach ($playersList as  $player){
            if(!in_array($player, $playersSuspended) && !in_array($player, $playerSelectionnable) ){
                $playerSelectionnable[] = $player;
            }
        }
        return  $this->render("admin/gamePlayersList.html.twig", [
            "game" => $game,
            "players" => $playerSelectionnable,
            "playerSuspended" => $playersSuspended,
            'idPlayers' => $idPlayers

        ]);
    }

    public function removeAllPlayer($game, $doctrine): void
    {
        $em = $doctrine->getManager();
        $players = $game->getPlayers();
        foreach ($players as $player){
            $game->removePlayer($player);
        }
        $em->persist($game);
        $em->flush();
    }

    public function getSuspendedPlayer($date, $category, $doctrine, $game):array
    {
        $suspended = [];
        $suspensions = $doctrine->getRepository(Suspension::class)->findSuspensionInFunctionOfDate($date);
        foreach ($suspensions as $suspension){
            $games = $doctrine->getRepository(Game::class)->findGamesInFunctionOfDateAndCat($suspension->getBeginDate(), $category);
            if(count($games) > $suspension->getNbGame()){
                if($games[$suspension->getNbGame() - 1 ]->getGameDate() >= $game->getGameDate() ){
                    $suspended[] = $suspension->getPlayer();
                }
            }
            else{
                $suspended[] = $suspension->getPlayer();
            }
        }
        return($suspended);
    }

    /**
     * @Route("/addPlayer", name="addPlayer")
     */
    public function addPlayer(): Response
    {
        $player = new Player();

        return  $this->render("admin/games.html.twig", [

        ] );
    }



    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion des Cartons')
            ->setFaviconPath("");
    }

    public function configureJoueurs(): Joueurs
    {
        return Dashboard::new()
            ->setTitle('Gestion des joueurs')
            ->setFaviconPath("")
            ->setTextDirection('players');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Feuilles de match', 'fa fa-pen'),
            MenuItem::linkToCrud('Cartons', "fa fa-hand-paper", Card::class),
            MenuItem::linkToCrud('Suspension', 'fa fa-ban ',Suspension::class),
            MenuItem::linkToCrud('Players', "fa fa-user", Player::class),
            MenuItem::linkToCrud('Game', "fa fa-futbol", Game::class),
            MenuItem::linkToCrud('Compétitions', "fa fa-futbol", Category::class),
        ];

    }
}

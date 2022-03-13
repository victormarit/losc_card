<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $gameDate;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="games")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="game")
     */
    private $cards;

    /**
     * @ORM\ManyToMany(targetEntity=Player::class, mappedBy="gameList")
     */
    private $players;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameDate(): ?\DateTimeInterface
    {
        return $this->gameDate;
    }

    public function setGameDate(\DateTimeInterface $gameDate): self
    {
        $this->gameDate = $gameDate;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setGame($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getGame() === $this) {
                $card->setGame(null);
            }
        }

        return $this;
    }


    //Custom function
    public function getCatName()
    {
        return $this->getCategory()->getName();
    }

    public function getCardsNumber()
    {
        return count($this->getCards());
    }

    public function getYellowCards()
    {
        $yellow = array();
        $cards = $this->getCards();
        foreach ($cards as $card){
            if($card->getCardType()->getName() == "Yellow"){
                array_push($yellow, $card);
            }
        }
        return count($yellow);
    }

    public function getRedCards()
    {
        $red = array();
        $cards = $this->getCards();
        foreach ($cards as $card){
            if($card->getCardType()->getName() == "Red"){
                array_push($red, $card);
            }
        }
        return count($red);
    }

    public function __toString() {
        return $this->name . " " . $this->getCatName() . " " . $this->getGameDate()->format("d/m/Y");
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->addGameList($this);
        }

        return $this;
    }


    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            $player->removeGameList($this);
        }

        return $this;
    }
}

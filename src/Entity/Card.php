<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=CardType::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cardType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getCardType(): ?CardType
    {
        return $this->cardType;
    }

    public function setCardType(?CardType $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function getCardColor(){
        if($this->getCardType()->getName() == "Yellow"){
            return "Jaune";
        }
        else{
            return "Rouge";
        }
    }

    //Custom function
    public function getNamePlayer(){
        return $this->getPlayer()->getLastname();
    }

    public function getFirstnamePlayer(){
        return $this->getPlayer()->getFirstname();
    }
    public function getCatName()
    {
        return $this->getGame()->getCategory()->getName();
    }
    public function getDateName()
    {
        return $this->getGame()->getGameDate();
    }

    public function getActiveCards(){
        
    }
}

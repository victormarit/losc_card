<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="player", cascade={"remove"})
     */
    private $cards;

    /**
     * @ORM\OneToMany(targetEntity=Suspension::class, mappedBy="player", cascade={"remove"})
     */
    private $suspensions;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, inversedBy="players", cascade={"remove"})
     */
    private $gameList;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->suspensions = new ArrayCollection();
        $this->gameList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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
            $card->setPlayer($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getPlayer() === $this) {
                $card->setPlayer(null);
            }
        }

        return $this;
    }

    //custom functions
    public function getYellowCardsNumber(){
        $yellow = array();
        $cards = $this->getCards();
        foreach ($cards as $card){
            if($card->getCardType()->getName() == "Yellow"){
                array_push($yellow, $card);
            }
        }
        return count($yellow);
    }

    public function getYellowCards(){
        $yellow = array();
        $cards = $this->getCards();
        foreach ($cards as $card){
            if($card->getCardType()->getName() == "Yellow"){
                array_push($yellow, $card);
            }
        }
        return $yellow;
    }

    public function getRedCardsNumber(){
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
        return $this->lastname . " " . $this->firstname;
    }

    /**
     * @return Collection|Suspension[]
     */
    public function getSuspensions(): Collection
    {
        return $this->suspensions;
    }

    public function addSuspension(Suspension $suspension): self
    {
        if (!$this->suspensions->contains($suspension)) {
            $this->suspensions[] = $suspension;
            $suspension->setPlayer($this);
        }

        return $this;
    }

    public function removeSuspension(Suspension $suspension): self
    {
        if ($this->suspensions->removeElement($suspension)) {
            // set the owning side to null (unless already changed)
            if ($suspension->getPlayer() === $this) {
                $suspension->setPlayer(null);
            }
        }

        return $this;
    }

    public function getActiveYellowCards($cardDate){
        $cards = array();
        $endDate =  new \DateTime($cardDate);
        $beginDate = new \DateTime($cardDate);
        $beginDate->modify("-3 month");
        foreach ($this->getSuspensions() as $suspension){
            if($suspension->getBeginDate()>$beginDate){
                $beginDate = $suspension->getBeginDate();

            }
        }
        foreach ($this->getYellowCards() as $card){
            if($card->getGame()->getGameDate()>=$beginDate and $card->getGame()->getGameDate()<=$endDate)
                array_push($cards, $card);
        }
        return $cards;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGameList(): Collection
    {
        return $this->gameList;
    }

    public function addGameList(Game $gameList): self
    {
        if (!$this->gameList->contains($gameList)) {
            $this->gameList[] = $gameList;
        }

        return $this;
    }

    public function removeGameList(Game $gameList): self
    {
        $this->gameList->removeElement($gameList);

        return $this;
    }

}

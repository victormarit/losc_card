<?php

namespace App\Entity;

use App\Repository\SuspensionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SuspensionRepository::class)
 */
class Suspension
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbGame;

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginDate;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="suspensions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbGame(): ?int
    {
        return $this->nbGame;
    }

    public function setNbGame(int $nbGame): self
    {
        $this->nbGame = $nbGame;

        return $this;
    }

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
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

    //custom functions
    public function getNamePlayer(){
        return $this->getPlayer()->getLastname();
    }
    public function getFirstnamePlayer(){
        return $this->getPlayer()->getFirstname();
    }
}

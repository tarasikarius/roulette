<?php

namespace App\Entity;

use App\Repository\BidRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BidRepository::class)
 */
class Bid
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"bid:post", "spin:view"})
     * @Assert\NotNull()
     * @Assert\Range(min=App\Entity\Spin::LOWEST_CELL, max=App\Entity\Spin::JACKPOT_CELL)
     */
    private $cell;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"bid:post", "spin:view"})
     * @Assert\NotNull()
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity=Spin::class, inversedBy="bids")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCell(): ?int
    {
        return $this->cell;
    }

    public function setCell(int $cell): self
    {
        $this->cell = $cell;

        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getSpin(): ?Spin
    {
        return $this->spin;
    }

    public function setSpin(?Spin $spin): self
    {
        $this->spin = $spin;

        if(!$spin->getBids()->contains($this)) {
            $spin->addBid($this);
        }

        return $this;
    }
}

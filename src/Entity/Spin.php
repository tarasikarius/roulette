<?php

namespace App\Entity;

use App\Repository\SpinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SpinRepository::class)
 */
class Spin
{
    const LOWEST_CELL = 1;
    const JACKPOT_CELL = 11;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     *  @Groups({"spin:view"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"spin:view"})
     * @Assert\NotNull()
     * @Assert\Range(min=App\Entity\Spin::LOWEST_CELL, max=App\Entity\Spin::JACKPOT_CELL)
     */
    private $winningCell;

    /**
     * @ORM\ManyToOne(targetEntity=Round::class, inversedBy="spins")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"spin:view"})
     * @Assert\NotNull()
     */
    private $round;

    /**
     * @ORM\OneToMany(targetEntity=Bid::class, mappedBy="spin")
     *
     * @Groups({"spin:view"})
     */
    private $bids;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="spins")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"spin:view"})
     * @Assert\NotNull()
     */
    private $initiator;


    public function __construct()
    {
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinningCell(): ?int
    {
        return $this->winningCell;
    }

    public function setWinningCell(int $winningCell): self
    {
        $this->winningCell = $winningCell;

        return $this;
    }

    public function getRound(): ?Round
    {
        return $this->round;
    }

    public function setRound(?Round $round): self
    {
        $this->round = $round;

        return $this;
    }

    /**
     * @return Collection|Bid[]
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setSpin($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->contains($bid)) {
            $this->bids->removeElement($bid);
            // set the owning side to null (unless already changed)
            if ($bid->getSpin() === $this) {
                $bid->setSpin(null);
            }
        }

        return $this;
    }

    public function getInitiator(): ?User
    {
        return $this->initiator;
    }

    public function setInitiator(?User $initiator): self
    {
        $this->initiator = $initiator;

        return $this;
    }
}

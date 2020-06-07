<?php

namespace App\Entity;

use App\Repository\RoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RoundRepository::class)
 */
class Round
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"spin:view", "user:statistic"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closedAt;

    /**
     * @ORM\OneToMany(targetEntity=Spin::class, mappedBy="round", orphanRemoval=true)
     */
    private $spins;

    public function __construct()
    {
        $this->spins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClosedAt(): ?\DateTimeInterface
    {
        return $this->closedAt;
    }

    public function setClosedAt(?\DateTimeInterface $closedAt): self
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    /**
     * @return Collection|Spin[]
     */
    public function getSpins(): Collection
    {
        return $this->spins;
    }

    public function addSpin(Spin $spin): self
    {
        if (!$this->spins->contains($spin)) {
            $this->spins[] = $spin;
            $spin->setRound($this);
        }

        return $this;
    }

    public function removeSpin(Spin $spin): self
    {
        if ($this->spins->contains($spin)) {
            $this->spins->removeElement($spin);
            // set the owning side to null (unless already changed)
            if ($spin->getRound() === $this) {
                $spin->setRound(null);
            }
        }

        return $this;
    }
}

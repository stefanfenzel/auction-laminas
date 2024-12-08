<?php

declare(strict_types=1);

namespace Auction\Domain\Auctions;

use Auction\Domain\Offers\Offer;
use Auction\Domain\Users\User;
use Auction\Infrastructure\Auctions\Repository\DoctrineAuctionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineAuctionRepository::class)]
#[ORM\Table(name: 'auctions')]
class Auction
{
    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'auction', orphanRemoval: true)]
    private Collection $offers;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'string', length: 36)]
        private ?string $id = null,
        #[ORM\ManyToOne(inversedBy: 'auctions')]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null,
        #[ORM\Column(length: 255)]
        private ?string $title = null,
        #[ORM\Column(type: Types::TEXT)]
        private ?string $description = null,
        #[ORM\Column(name: 'start_price')]
        private ?float $startPrice = null,
        #[ORM\Column(name: 'end_date')]
        private ?\DateTimeImmutable $endDate = null,
        #[ORM\Column(name: 'created_at')]
        private ?\DateTimeImmutable $createdAt = null,
        #[ORM\Column(name: 'updated_at')]
        private ?\DateTimeImmutable $updatedAt = null,
    ) {
        $this->offers = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStartPrice(): ?float
    {
        return $this->startPrice;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (! $this->offers->contains($offer)) {
            $this->offers->add($offer);
        }

        return $this;
    }

    public function getHighestOffer(): float|null
    {
        $latestOffer = $this->offers->last();

        if ($latestOffer && $latestOffer->getBidAmount() > $this->startPrice) {
            return $latestOffer->getBidAmount();
        }

        return null;
    }
}

<?php

declare(strict_types=1);

namespace Auction\Domain\Auctions;

use Auction\Domain\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

interface AuctionRepositoryInterface
{
    public function findById(Uuid $id): ?Auction;

    public function findByUserId(int $userId): ArrayCollection;

    public function findRunningAuctions(): ArrayCollection;

    public function save(Auction $auction): void;

    public function delete(Uuid $id): void;
}

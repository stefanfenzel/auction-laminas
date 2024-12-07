<?php

declare(strict_types=1);

namespace Auction\Domain\Offers;

interface OfferRepositoryInterface
{
    public function save(Offer $offer): void;
}

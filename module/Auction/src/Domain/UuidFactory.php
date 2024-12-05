<?php

declare(strict_types=1);

namespace Auction\Domain;

interface UuidFactory
{
    public function create(): Uuid;
}

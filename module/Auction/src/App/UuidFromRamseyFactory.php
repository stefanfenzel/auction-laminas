<?php

declare(strict_types=1);

namespace Auction\App;

use Auction\Domain\UuidFactory;
use Auction\Domain\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

final class UuidFromRamseyFactory implements UuidFactory
{
    public function create(): Uuid
    {
        return Uuid::fromString(RamseyUuid::uuid7()->toString());
    }
}

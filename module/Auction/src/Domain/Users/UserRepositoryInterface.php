<?php

declare(strict_types=1);

namespace Auction\Domain\Users;

use Auction\Domain\Users\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;
}

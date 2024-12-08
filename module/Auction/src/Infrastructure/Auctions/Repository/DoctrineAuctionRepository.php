<?php

declare(strict_types=1);

namespace Auction\Infrastructure\Auctions\Repository;

use Auction\Domain\Auctions\Auction;
use Auction\Domain\Auctions\AuctionRepositoryInterface;
use Auction\Domain\Uuid;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineAuctionRepository implements AuctionRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(Uuid $id): ?Auction
    {
        return $this->entityManager->createQueryBuilder()
            ->select('a')
            ->from(Auction::class, 'a')
            ->where('a.id = :id')
            ->setParameter('id', $id->toString())
            ->orderBy('a.createdAt', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByUserId(int $userId): ArrayCollection
    {
        $query = $this->entityManager->createQueryBuilder()
            ->select('a')
            ->from(Auction::class, 'a')
            ->where('a.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('a.createdAt', 'ASC')
            ->getQuery();

        return new ArrayCollection($query->getResult());
    }

    public function findRunningAuctions(): ArrayCollection
    {
        $query = $this->entityManager->createQueryBuilder()
            ->select('a')
            ->from(Auction::class, 'a')
            ->where('a.endDate > :now')
            ->setParameter('now', new DateTime())
            ->orderBy('a.createdAt', 'ASC')
            ->getQuery();

        return new ArrayCollection($query->getResult());
    }

    public function save(Auction $auction): void
    {
        $this->entityManager->persist($auction);
        $this->entityManager->flush();
    }

    public function delete(Uuid $id): void
    {
        $auction = $this->findById($id);

        if ($auction === null) {
            return;
        }

        $this->entityManager->remove($auction);
        $this->entityManager->flush();
    }
}

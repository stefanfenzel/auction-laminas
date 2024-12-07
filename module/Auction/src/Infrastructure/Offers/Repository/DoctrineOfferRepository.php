<?php

namespace Auction\Infrastructure\Offers\Repository;

use Auction\Domain\Offers\Offer;
use Auction\Domain\Offers\OfferRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 */
class DoctrineOfferRepository extends ServiceEntityRepository implements OfferRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function save(Offer $offer): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($offer);
        $entityManager->flush();
    }
}

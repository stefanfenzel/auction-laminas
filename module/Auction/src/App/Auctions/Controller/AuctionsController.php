<?php

declare(strict_types=1);

namespace Auction\App\Auctions\Controller;

use Auction\Domain\UuidFactory;
use Auction\Infrastructure\Auctions\Repository\DoctrineAuctionRepository;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class AuctionsController extends AbstractActionController
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
        private readonly DoctrineAuctionRepository $repository,
    )
    {
    }

    public function indexAction()
    {
        $auctions = $this->repository->findRunningAuctions();

        return new ViewModel([
            'auctions' => $auctions,
        ]);
    }

    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}

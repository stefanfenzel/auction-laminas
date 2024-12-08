<?php

declare(strict_types=1);

namespace Auction\App\Auctions\Controller;

use Auction\Domain\Auctions\AuctionRepositoryInterface;
use Auction\Domain\Uuid;
use Auction\Domain\UuidFactory;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class AuctionsController extends AbstractActionController
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
        private readonly AuctionRepositoryInterface $repository,
    )
    {
    }

    public function homeAction(): ViewModel
    {
        $auctions = $this->repository->findRunningAuctions();

        $view = new ViewModel([
            'auctions' => $auctions,
        ]);
        $view->setTemplate('auction/home');

        return $view;
    }

    public function dashboardAction()
    {
    }

    public function showAction(): ViewModel
    {
        $id = $this->params()->fromRoute('id');
        $auction = $this->repository->findById(Uuid::fromString($id));

        $view = new ViewModel([
            'auction' => $auction,
        ]);
        $view->setTemplate('auction/show');

        return $view;
    }
}

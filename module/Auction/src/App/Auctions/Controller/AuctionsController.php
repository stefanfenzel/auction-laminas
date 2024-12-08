<?php

declare(strict_types=1);

namespace Auction\App\Auctions\Controller;

use Auction\Domain\Auctions\AuctionRepositoryInterface;
use Auction\Domain\Users\User;
use Auction\Domain\Users\UserRepositoryInterface;
use Auction\Domain\Uuid;
use Auction\Domain\UuidFactory;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class AuctionsController extends AbstractActionController
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
        private readonly AuctionRepositoryInterface $auctionRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthenticationService $auth,
    ) {
    }

    public function homeAction(): ViewModel
    {
        $auctions = $this->auctionRepository->findRunningAuctions();

        $view = new ViewModel([
            'auctions' => $auctions,
            'identity' => $this->auth->getIdentity(),
        ]);
        $view->setTemplate('auction/home');
        $this->layout()->setVariable('identity', $this->auth->getIdentity());

        return $view;
    }

    public function dashboardAction(): Response|ViewModel
    {
        $email = $this->auth->getIdentity();
        if ($email === null) {
            return $this->redirect()->toRoute('login');
        }

        $auctions = $this->auctionRepository->findRunningAuctions();

        $view = new ViewModel([
            'auctions' => $auctions,
            'identity' => $email,
        ]);
        $view->setTemplate('auction/dashboard');
        $this->layout()->setVariable('identity', $email);

        return $view;
    }

    public function showAction(): ViewModel
    {
        $id = $this->params()->fromRoute('id');
        $auction = $this->auctionRepository->findById(Uuid::fromString($id));

        $view = new ViewModel([
            'auction' => $auction,
        ]);
        $view->setTemplate('auction/show');

        return $view;
    }

    public function auctionsAction(): Response|ViewModel
    {
        $email = $this->auth->getIdentity();
        if ($email === null) {
            return $this->redirect()->toRoute('login');
        }

        /** @var User $user */
        $user = $this->userRepository->findByEmail($email);
        $auctions = $this->auctionRepository->findByUserId($user->getId());

        $view = new ViewModel([
            'auctions' => $auctions,
            'identity' => $email,
        ]);
        $view->setTemplate('auction/auctions');
        $this->layout()->setVariable('identity', $email);

        return $view;
    }
}

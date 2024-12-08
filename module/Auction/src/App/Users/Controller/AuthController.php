<?php

declare(strict_types=1);

namespace Auction\App\Users\Controller;

use Auction\Domain\Users\User;
use Auction\Domain\Users\UserRepositoryInterface;
use Auction\Domain\UuidFactory;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class AuthController extends AbstractActionController
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidFactory $uuidFactory,
    ) {
    }

    public function loginAction(): Response|ViewModel
    {
        if (! $this->getRequest()->isPost()) {
            $view = new ViewModel();
            $view->setTemplate('auth/login');

            return $view;
        }

        $email = $this->params()->fromPost('email');
        $password = $this->params()->fromPost('password');
        $dbAdapter = $this->getDbAdapter();
        $passwordValidation = function ($hash, $password) {
            return password_verify($password, $hash);
        };

        $authAdapter = new AuthAdapter($dbAdapter);
        $authAdapter
            ->setTableName('users')
            ->setIdentityColumn('email')
            ->setCredentialColumn('password')
            ->setCredentialValidationCallback($passwordValidation)
            ->setIdentity($email)
            ->setCredential($password);

        $auth = new AuthenticationService();
        $result = $auth->authenticate($authAdapter);

        if (! $result->isValid()) {
            $view = new ViewModel([
                'error' => 'Invalid credentials',
            ]);
            $view->setTemplate('auth/login');

            return $view;
        }

        return $this->redirect()->toRoute('dashboard');
    }

    public function logoutAction(): Response
    {
        $auth = new AuthenticationService();
        $auth->clearIdentity();

        return $this->redirect()->toRoute('home');
    }

    public function register(): Response|ViewModel
    {
        if (! $this->getRequest()->isPost()) {
            $view = new ViewModel();
            $view->setTemplate('auth/register');

            return $view;
        }

        $name = $this->params()->fromPost('name');
        $email = $this->params()->fromPost('email');
        $password = $this->params()->fromPost('password');
        $repeatPassword = $this->params()->fromPost('repeat_password');

        if ($password !== $repeatPassword) {
            $view = new ViewModel([
                'error' => 'Passwords do not match',
            ]);
            $view->setTemplate('auth/register');

            return $view;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $newUser = new User(
            $this->uuidFactory->create()->toString(), // todo implement uuid for user
            $name,
            $email,
            $passwordHash,
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );

        $this->userRepository->save($newUser);

        return $this->redirect()->toRoute('login');
    }

    private function getDbAdapter(): DbAdapter
    {
        $connection = $this->entityManager->getConnection()->getParams();

        return new DbAdapter([
            'driver' => 'Pdo_Mysql',
            'database' => $connection['dbname'],
            'username' => $connection['user'],
            'password' => $connection['password'],
            'hostname' => $connection['host'],
            'port' => $connection['port'],
        ]);
    }
}

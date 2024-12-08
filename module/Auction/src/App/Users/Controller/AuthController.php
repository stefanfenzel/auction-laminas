<?php

declare(strict_types=1);

namespace Auction\App\Users\Controller;

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

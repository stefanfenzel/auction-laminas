<?php

declare(strict_types=1);

namespace Auction\App\Users\Controller;

use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class AuthController extends AbstractActionController
{
    public function loginAction(): Response|ViewModel
    {
        $email = $this->params()->fromPost('email');
        $password = $this->params()->fromPost('password');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $authAdapter = new CredentialTreatmentAdapter($email, $hashedPassword);

        $auth = new AuthenticationService();
        $result = $auth->authenticate($authAdapter);

        if (! $result->isValid()) {
            return new ViewModel([
                'error' => 'Invalid credentials',
            ]);
        }

        return $this->redirect()->toRoute('dashboard');
    }
}

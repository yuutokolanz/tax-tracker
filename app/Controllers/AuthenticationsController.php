<?php

namespace App\Controllers;

use App\Models\Accountants;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthenticationsController extends Controller
{
    protected string $layout = 'login';

    public function new(): void
    {
        $this->render('authentications/new');
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $accountant = Accountants::findByEmail($params['email']);

        if ($accountant && $accountant->authenticate($params['password'])) {
            Auth::login($accountant);

            FlashMessage::success('Login realizado com sucesso!');
            $this->redirectTo(route('index'));
        } else {
            FlashMessage::danger('Email e/ou senha inválidos');
            $this->redirectTo(route('accountants.login'));
        }
    }

    public function logout(): void
    {
        Auth::logout();
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo(route('accountants.login'));
    }
}

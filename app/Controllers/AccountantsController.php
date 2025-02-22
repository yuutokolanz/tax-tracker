<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\FlashMessage;

class AccountantsController extends Controller
{
    public function all(): void
    {
        $title = 'Todos os Contadores que Supervisiono';
        $this->render('accountants/all', compact('title'));
    }

    public function new(): void
    {
        $title = 'Novo Contador';
        $this->render('accountants/new', compact('title'));
    }

    public function profile(): void
    {
        $title = 'Perfil';
        $this->render('accountants/show-profile', compact('title'));
    }

    public function updateProfileImage(): void
    {
        $image = $_FILES['accountant_image'];

        if ($this->currentUser()->avatar()->update($image)) {
            FlashMessage::success('Imagem de perfil atualizada com sucesso!');
            $this->redirectTo(route('accountants.profile'));
        } else {
            FlashMessage::danger('Erro ao atualizar imagem de perfil! Formato ou tamanho inválido(s).');
            $this->redirectTo(route('accountants.profile'));
        }
    }

    public function deleteProfileImage(): void
    {
        $this->currentUser()->avatar()->delete();
        $this->redirectTo(route('accountants.profile'));
    }
}

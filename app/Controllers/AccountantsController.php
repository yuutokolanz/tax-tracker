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

        if ($this->currentUser()->avatar()->validateImageFormat($image)){
            if ($this->currentUser()->avatar()->validateImageSize($image)){
                $this->currentUser()->avatar()->upload($image);
                $this->redirectTo(route('accountants.profile'));
            } else{
                FlashMessage::danger('Imagem muito grande. Por favor, envie uma imagem com até 2MB.');
                $this->redirectTo(route('accountants.profile'));
            }
        } else {
            FlashMessage::danger('Formato de imagem inválido. Por favor, envie uma imagem no formato PNG, JPG ou JPEG.');
            $this->redirectTo(route('accountants.profile'));
        }
    }

    public function deleteProfileImage(): void
    {
        $this->currentUser()->avatar()->delete();
        $this->redirectTo(route('accountants.profile'));
    }
}

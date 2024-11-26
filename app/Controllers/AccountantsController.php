<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

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
}

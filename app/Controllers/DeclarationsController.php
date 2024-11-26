<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class DeclarationsController extends Controller
{
    public function new(): void
    {
        $title = 'Nova Declaração';
        $this->render('declarations/new', compact('title'));
    }

    public function pending(): void
    {
        $title = 'Declarações Pendentes';
        $this->render('declarations/pending', compact('title'));
    }

    public function finished(): void
    {
        $title = 'Declarações Finalizadas';
        $this->render('declarations/finished', compact('title'));
    }

    public function my(): void
    {
        $title = 'Minhas Declarações';
        $this->render('declarations/my', compact('title'));
    }

    public function show(int $id): void
    {
        $title = 'Declaração ';
        $this->render('declarations/show', compact('title'));
    }

    // Role Supervisor - role_id = 2
    public function all(): void
    {
        $title = 'Todas as Declarações';
        $this->render('declarations/all', compact('title'));
    }
}

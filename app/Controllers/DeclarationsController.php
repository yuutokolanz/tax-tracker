<?php

namespace App\Controllers;

use App\Models\Clients;
use App\Models\Declarations;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class DeclarationsController extends Controller
{
    public function new(): void
    {
        $clients = Clients::all();
        $title = 'Nova Declaração';
        $this->render('declarations/new', compact('title', 'clients'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        if (!isset($params['client_id'])) {
            FlashMessage::danger('Por favor selecione um cliente!');
            $this->redirectTo(route('declarations.new'));
        } elseif (!isset($params['declaration'])) {
            FlashMessage::danger('Por favor preencha os campos corretamente!');
            $this->redirectTo(route('declarations.new'));
        }

        $client = Clients::findById($params['client_id']);
        $declaration = $client->declarations()->new($params['declaration']);
        $declaration->status = "Iniciado e aguardando documentação";
        if ($declaration->save()) {
            FlashMessage::success('Declaração cadastrada com sucesso!');
            $this->redirectTo(route('declarations.my'));
        } else {
            FlashMessage::danger('Erro ao cadastrar declaração!');
            $title = 'Nova Declaração';
            $clients = Clients::all();
            $this->render('declarations/new', compact('title', 'clients', 'declaration'));
        }
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');
        $declaration = Declarations::findById($id);
        if ($declaration->destroy()) {
            FlashMessage::success('Declaração excluída com sucesso!');
        } else {
            FlashMessage::danger('Erro ao excluir declaração!');
        }
        $this->redirectTo(route('declarations.my'));
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

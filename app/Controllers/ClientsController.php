<?php

namespace App\Controllers;

use App\Models\Clients;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class ClientsController extends Controller
{
    public function index(Request $request): void
    {
        $paginator = Clients::paginate(page: $request->getParam('page', 1), route: 'clients.index');
        $clients = $paginator->registers();

        $title = 'Clientes Registrados';
        $this->render('clients/index', compact('paginator', 'clients', 'title'));
    }
    public function new(): void
    {
        $title = 'Novo Cliente';
        $client = new Clients();

        $this->render('clients/new', compact('client', 'title'));
    }
    public function create(Request $request): void
    {
        $params = $request->getParams();
        $client = new Clients($params['client']);

        if ($client->save()) {
            FlashMessage::success('Cliente cadastrado com sucesso!');
            $this->redirectTo(route('clients.index'));
        } else {
            FlashMessage::danger('Erro ao cadastrar cliente!');
            $title = 'Novo Cliente';
            $this->render('clients/new', compact('client', 'title'));
        }
    }
    public function show(Request $request): void
    {
        $client = Clients::findById($request->getParam('id'));

        if ($client) {
            $title = "Detalhes do cliente {$client->name}";
            $this->render('clients/show', compact('client', 'title'));
        } else {
            FlashMessage::danger('Cliente não encontrado!');
            $this->redirectTo(route('clients.index'));
        }
    }

    public function edit(Request $request): void
    {
        $client = Clients::findById($request->getParam('id'));

        if ($client) {
            $title = "Editar cliente {$client->name}";
            $this->render('clients/edit', compact('client', 'title'));
        } else {
            FlashMessage::danger('Cliente não encontrado!');
            $this->redirectTo(route('clients.index'));
        }
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();
        $client = Clients::findById($request->getParam('id'));

        if ($client) {
            if ($client->update($params['client'])) {
                FlashMessage::success('Cliente atualizado com sucesso!');
                $this->redirectTo(route('clients.index'));
            } else {
                FlashMessage::danger('Erro ao atualizar cliente!');
                $title = "Editar cliente {$client->name}";
                $this->render('clients/edit', compact('client', 'title'));
            }
        } else {
            FlashMessage::danger('Cliente não encontrado!');
            $this->redirectTo(route('clients.index'));
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $client = Clients::findById($params['id']);
        $client->destroy();

        FlashMessage::success('Cliente excluído com sucesso!');
        $this->redirectTo(route('clients.index'));
    }
}

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
}

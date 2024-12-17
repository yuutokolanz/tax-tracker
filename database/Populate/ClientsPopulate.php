<?php

namespace Database\Populate;

use App\Models\Clients;

class ClientsPopulate
{
  public static function populate(): void
  {
    $numberOfClients = 20;

    for ($i = 0; $i < $numberOfClients; $i++) {
      $data = [
        'name' => 'Cliente Teste' . $i,
        'cpf' =>   str_pad(mt_rand(0, 99999999999), 11, '0', STR_PAD_LEFT),
        'email' => 'cliente' . $i . '@clientes.com',
      ];

      $client = new Clients($data);
      $client->save();
    }
    echo "Clients populated with $numberOfClients register \n";
  }
}

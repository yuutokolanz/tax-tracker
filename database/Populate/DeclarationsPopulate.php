<?php

namespace  Database\Populate;

use App\Models\Clients;

class DeclarationsPopulate{
  public static function populate(): void {
    $declarationsPerClient = 5;

    $clients = Clients::all();
    
    foreach ($clients as $client) {
      for ($i=0; $i < $declarationsPerClient ; $i++) { 
        $data = [
          'year' => 2025,
          'status' => 'Em análise ' . $i,
          'tax_due' => 20.50 + $i,
          'tax_return' => 5.55 + $i
        ];
        $declaration = $client->declarations()->new($data);
        $declaration->save();
      }
    }
    echo "Each client was populated with $declarationsPerClient registers \n";
  }
}
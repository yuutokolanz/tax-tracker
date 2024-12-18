<?php

namespace Tests\Unit\Models;

use App\Models\Clients;
use Tests\TestCase;

class ClientsTest extends TestCase
{
  private Clients $client;

  public function setUp(): void
  {
    parent::setUp();

    $this->client = new Clients([
      'name' => 'Test Client',
      'cpf' => '12345678901',
      'email' => 'test@test.com'
    ]);
    $this->client->save();
  }
  
  public function test_should_create_new_client(): void
  {
    $client2 = new Clients([
      'name' => 'Test Client 2',
      'cpf' => '12345678902',
      'email' => 'test2@test.com'
    ]);
    $this->assertTrue($client2->save());
    $this->assertCount(2, Clients::all());
  }

  public function test_all_should_return_all_clients(): void
  {
    $clients[] = $this->client;
    $clients[] = new Clients([
      'name' => 'Test Client 2',
      'cpf' => '12345678902',
      'email' => 'test2@test.com'
    ]);
    $clients[1]->save();

    $all = Clients::all();
    $this->assertCount(2, $all);
    $this->assertEquals($clients, $all);
  }

  public function test_destroy_should_remove_client(): void
  {
    $client2 = new Clients([
      'name' => 'Test Client 2',
      'cpf' => '12345678902',
      'email' => 'test2@test.com'
    ]);

    $client2->save();
    $client2->destroy();

    $this->assertCount(1, Clients::all());
  }

  public function test_sets(): void {
    $client = new Clients();
    $client->id = 8;
    $client->name = 'Test Client 2';
    $client->cpf = '12345678902';
    $client->email = 'test2@test.com';

    $this->assertEquals(8, $client->id);
    $this->assertEquals('Test Client 2', $client->name);
    $this->assertEquals('12345678902', $client->cpf);
    $this->assertEquals('test2@test.com', $client->email);
  }

  public function test_errors_should_return_name_error(): void
  {
    $client = new Clients([
      'name' => 'Test Client',
      'cpf' => '12345678901',
      'email' => 'test@test.com'
    ]);
    $client->name = '';

    $this->assertFalse($client->isValid());
    $this->assertFalse($client->save());
    $this->assertFalse($client->hasErrors());

    $this->assertEquals('não pode ser vazio!', $client->errors('name'));
  }

  public function test_errors_should_return_cpf_error(): void
  {
    $client = new Clients([
      'name' => 'Test Client',
      'cpf' => '12345678901',
      'email' => 'test@test.com'
    ]);
    $client->cpf = '';

    $this->assertFalse($client->isValid());
    $this->assertFalse($client->save());
    $this->assertFalse($client->hasErrors());

    $this->assertEquals('deve ter entre 11 e 11 caracteres!', $client->errors('cpf'));
  }

  public function test_errors_should_return_email_error(): void
  {
    $client = new Clients([
      'name' => 'Test Client',
      'cpf' => '12345678901',
      'email' => 'test@test.com'
    ]);
    $client->email = '';

    $this->assertFalse($client->isValid());
    $this->assertFalse($client->save());
    $this->assertFalse($client->hasErrors());

    $this->assertEquals('não pode ser vazio!', $client->errors('email'));
  }

  public function test_uniques_cpf(): void
  {
    $clients[] = $this->client;
    $clients[] = new Clients([
      'name' => 'Test Client 2',
      'cpf' => '12345678901',
      'email' => 'test2@test.com'
    ]);
    
    $this->assertFalse($clients[1]->isValid());
    $this->assertFalse($clients[1]->save());
    $this->assertFalse($clients[1]->hasErrors());
  }

  public function test_should_find_by_id(): void
  {
    $client2 = new Clients(['name' => 'Test Client 2', 'cpf' => '12345678902', 'email' => 'test2@test.com']);
    $client1 = new Clients(['name' => 'Test Client', 'cpf' => '12345678904', 'email' => 'test@test.com']);
    $client3 = new Clients(['name' => 'Test Client 3', 'cpf' => '12345678903', 'email' => 'test3@test.com']);

    $client1->save();
    $client2->save();
    $client3->save();

    $this->assertEquals($client1, Clients::findById($client1->id));
  }

  public function test_find_by_id_should_return_null(): void
  {
    $client2 = new Clients(['name' => 'Test Client 2', 'cpf' => '12345678902', 'email' => 'test2@test.com']);
    $client2->save();

    $this->assertNull(Clients::findById(8));
  }
}
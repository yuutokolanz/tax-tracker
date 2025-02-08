<?php

namespace Tests\Unit\Models;

use App\Models\Clients;
use App\Models\Declarations;
use Tests\TestCase;

class DeclarationsTest extends TestCase
{
    private Declarations $declaration;
    private Clients $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = new Clients([
        'name' => 'Cliente 1',
        'cpf' => '12345678910',
        'email' => 'test@test.com'
        ]);
        $this->client->save();

        $this->declaration = new Declarations([
        'year' => 2025,
        'status' => 'Teste',
        'tax_due' => 0,
        'tax_return' => 0,
        'client_id' => $this->client->id
        ]);
        $this->declaration->save();
    }

    public function test_should_create_new_declaration(): void
    {
        $this->assertTrue($this->declaration->save());
        $this->assertCount(1, Declarations::all());
    }

    public function test_destroy_should_remove_the_declaration(): void
    {
        $declaration2 = $this->client->declarations()->new([
        'year' => 2025,
        'status' => 'testando',
        'tax_due' => 0,
        'tax_return' => 0
        ]);

        $declaration2->save();
        $declaration2->destroy();

        $this->assertCount(1, Declarations::all());
    }

    public function test_all_should_return_all_declarations(): void
    {
        $declarations[] = $this->declaration;
        $declarations[] = $this->client->declarations()->new([
        'year' => 2025,
        'status' => 'testando',
        'tax_due' => 0,
        'tax_return' => 0
        ]);

        $declarations[1]->save();
        $all = Declarations::all();

        $this->assertCount(2, $all);
        $this->assertEquals($declarations, $all);
    }

    public function test_should_return_year_error(): void
    {
        $declaration = $this->client->declarations()->new([
        'status' => 'Teste',
        'tax_due' => 0,
        'tax_return' => 0,
        ]);

        $this->assertFalse($declaration->save());
        $this->assertEquals('não pode ser vazio!', $declaration->errors('year'));
    }

    public function test_should_return_status_error(): void
    {
        $declaration = $this->client->declarations()->new([
        'year' => 2025,
        'tax_due' => 0,
        'tax_return' => 0,
        'client_id' => $this->client->id
        ]);

        $this->assertFalse($declaration->save());
        $this->assertEquals('não pode ser vazio!', $declaration->errors('status'));
    }

    public function test_should_return_client_id_error(): void
    {
        $declaration = new Declarations([
        'year' => 2025,
        'status' => 'Teste',
        'tax_due' => 0,
        'tax_return' => 0
        ]);

        $this->assertFalse($declaration->save());
        $this->assertEquals('não pode ser vazio!', $declaration->errors('client_id'));
    }
}

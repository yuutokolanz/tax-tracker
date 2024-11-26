<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Accountants;

class AccountantsTest extends TestCase
{
    private Accountants $accountant;
    public function setUp(): void
    {
        parent::setUp();

        $this->accountant = new Accountants([
            'name' => 'Test Accountant',
            'email' => 'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $this->accountant->save();
    }

    public function test_not_empty_validation(): void
    {
        $accountant = new Accountants();
        $accountant->name = '';
        $accountant->email = '';

        $this->assertFalse($accountant->isValid());
        $this->assertFalse($accountant->save());
        $this->assertFalse($accountant->hasErrors());

        $this->assertEquals('não pode ser vazio!', $accountant->errors('name'));
        $this->assertEquals('não pode ser vazio!', $accountant->errors('email'));
    }

    public function test_should_create_accountant(): void
    {
        $accountant = new Accountants([
            'name' => 'Test Accountant',
            'email' => 'test@test2.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $this->assertTrue($accountant->save());
        $this->assertCount(2, Accountants::all());
    }

    public function test_password_confirmation_validation(): void
    {
        $accountant2 = new Accountants([
            'name' => 'Test Accountant',
            'email' => 'test@test1.com',
            'password' => '123456',
            'password_confirmation' => '654321'
        ]);

        $this->assertFalse($accountant2->isValid());
        $this->assertFalse($accountant2->save());

        $this->assertEquals('as senhas devem ser idênticas!', $accountant2->errors('password'));
    }

    public function test_authenticate(): void
    {
        $this->assertTrue($this->accountant->authenticate('123456'));
        $this->assertFalse($this->accountant->authenticate('654321'));
    }

    public function test_find_by_email(): void
    {
        $foundAccountant = Accountants::findByEmail('test@test.com');
        $this->assertInstanceOf(Accountants::class, $foundAccountant);
    }

    public function test_password_hashing(): void
    {
        $this->assertNotNull($this->accountant->encrypted_password);
        $this->assertTrue(password_verify('123456', $this->accountant->encrypted_password));
    }

    public function test_uniqueness_validation(): void
    {
        $accountant2 = new Accountants([
            'name' => 'Test Accountant',
            'email' => 'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $accountant2->save();

        $this->assertEquals('já existe um registro com esse dado', $accountant2->errors('email'));
    }
}

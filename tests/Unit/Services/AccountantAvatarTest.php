<?php

namespace Tests\Unit\Services;

use App\Models\Accountants;
use App\Services\AccountantAvatar;
use Tests\TestCase;

class AccountantAvatarTest extends TestCase
{
    private AccountantAvatar $accountantAvatar;
    private Accountants $accountant;

    /** @var array<string, mixed> $image */
    private array $image;

    public function setUp(): void
    {
        parent::setUp();


        $this->accountant = new Accountants([
        'name' => 'Teste 1',
        'email' => 'test1@test.com',
        'password' => '123456',
        'password_confirmation' => '123456',
        'role_id' => '1'
        ]);
        $this->accountant->save();

        $tmpFile = tempnam(sys_get_temp_dir(), 'php');
        $this->image = [
        'name' => 'avatar_test.jpg',
        'full_path' => 'avatar_test.jpg',
        'type' => 'image/jpg',
        'tmp_name' => $tmpFile,
        'error' => 0,
        'size' => filesize($tmpFile)
        ];

        $this->accountantAvatar = new AccountantAvatar(
            model: $this->accountant,
            validations: [
            'extensions' => ['jpg', 'png'],
            'max_size' => 2 * 1024 * 1024,
            ]
        );
    }

    public function test_upload_avatar(): void
    {
        $accountantAvatar = $this->getMockBuilder(AccountantAvatar::class)
        ->setConstructorArgs([$this->accountant, [
        'extensions' => ['jpg', 'png'],
        'max_size' => 2 * 1024 * 1024,
        ]])
        ->onlyMethods(['updateFile'])
        ->getMock();

        $accountantAvatar->expects($this->once())
        ->method('updateFile')
        ->willReturn(true);

        $resp = $accountantAvatar->update($this->image);
        $this->assertTrue($resp);

        $this->assertEquals($this->accountant->avatar_name, 'avatar.jpg');
    }

    public function test_update_avatar_invalid_extension(): void
    {
        $this->image['name'] = 'avatar.txt';
        $resp = $this->accountantAvatar->update($this->image);

        $this->assertFalse($resp);
    }

    public function test_update_avatar_invalid_size(): void
    {
        $this->image['size'] = 3 * 1024 * 1024; // 3MB
        $resp = $this->accountantAvatar->update($this->image);

        $this->assertFalse($resp);
    }
}

<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Lib\Validations;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property int $role_id
 */

class Accountants extends Model
{
    protected static string $table = 'accountants';
    protected static array $columns = ['id', 'name', 'email', 'encrypted_password', 'role_id'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('email', $this);

        Validations::uniqueness('email', $this);

        if ($this->newRecord()) {
            Validations::passwordConfirmation($this);
        }
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }
}

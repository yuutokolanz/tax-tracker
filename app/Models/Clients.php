<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Lib\Validations;


/**
 * @property int $id
 * @property string $name
 * @property string $cpf
 * @property string $email
 */
class Clients extends Model
{
  protected static string $table = 'clients';
  protected static array $columns = ['id', 'name', 'cpf', 'email'];

  public function validates(): void
  {
    Validations::notEmpty('name', $this);
    Validations::notEmpty('email', $this);
    Validations::notEmpty('cpf', $this);
    Validations::uniqueness('cpf', $this);
    Validations::correctLength('cpf', $this, 11, 11);
  }

  public static function findByCpf(string $cpf): Clients | null
  {
    return Clients::findBy(['cpf' => $cpf]);
  }
}

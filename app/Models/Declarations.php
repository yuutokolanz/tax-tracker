<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\Model;
use Lib\Validations;

/**
 * @property int $id
 * @property int $client_id
 * @property int year
 * @property string status
 * @property float tax_due
 * @property float tax_return
 */
class Declarations extends Model{
  protected static string $table = 'declarations';
  protected static array $columns = ['id', 'client_id', 'year',  'status', 'tax_due', 'tax_return'];

  public function client(): BelongsTo
  {
    return $this->belongsTo(Clients::class, 'client_id');
  }

  public function validates(): void
  {
    Validations::notEmpty('client_id', $this);
    Validations::notEmpty('year', $this);
    Validations::notEmpty('status', $this);
    Validations::notEmpty('tax_due', $this);
    Validations::notEmpty('tax_return', $this);
  }
}
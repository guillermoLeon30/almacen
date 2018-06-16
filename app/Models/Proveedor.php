<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
  protected $table = 'proveedor';
  public $timestamps = false;
  protected $fillable = [
                          'empresa',
                          'telefono',
                          'correo',
                          'descripcion',
                          'ciudad',
                          'provincia',
                          'pais'
                        ];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
  protected $table = 'actores';
  protected $fillable = ['nombre', 'tipo', 'lat', 'lng'];
}

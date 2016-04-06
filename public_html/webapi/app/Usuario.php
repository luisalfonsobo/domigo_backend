<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuario';

    public $timestamps = false;
}

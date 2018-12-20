<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $casts = ['lecturer' => 'string', 'location' => 'string', 'day' => 'string', 'time' => 'string'];

    
}

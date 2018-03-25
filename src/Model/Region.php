<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;
    protected $table = 'regions';
    protected $visible = [
        'id', 'name', 'region',
    ];

    public function departments()
    {
        return $this->hasMany('App\Model\Department');
    }
}

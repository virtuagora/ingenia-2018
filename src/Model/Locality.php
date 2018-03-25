<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    public $timestamps = false;
    protected $table = 'localities';
    protected $visible = [
        'id', 'name', 'custom', 'department_id',
    ];

    public function department()
    {
        return $this->belongsTo('App\Model\Department');
    }
}

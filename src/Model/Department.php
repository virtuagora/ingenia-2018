<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = false;
    protected $table = 'departments';
    protected $visible = [
        'id', 'name', 'region_id',
    ];

    public function region()
    {
        return $this->belongsTo('App\Model\Region');
    }

    public function localities()
    {
        return $this->hasMany('App\Model\Locality');
    }
}

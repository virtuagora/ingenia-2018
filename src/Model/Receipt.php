<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reicipt extends Model
{
    protected $table = 'reicipts';
    protected $visible = [
        'id', 'detail', 'ammount', 'date', 'file', 'created_at',
    ];
    
    public function project()
    {
        return $this->belongsTo('App\Model\Project', 'project_id');
    }
}

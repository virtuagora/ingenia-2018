<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';
    protected $visible = [
        'id', 'detail', 'amount', 'date', 'file', 'created_at',
    ];
    
    public function project()
    {
        return $this->belongsTo('App\Model\Project', 'project_id');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Node extends Model
{
    use SoftDeletes;

    protected $table = 'nodes';
    protected $dates = ['deleted_at', 'close_date'];
    protected $visible = [
        'id', 'title', 'points', 'author', 'created_at',
    ];

    public function author()
    {
        return $this->belongsTo('App\Model\Subject');
    }
}

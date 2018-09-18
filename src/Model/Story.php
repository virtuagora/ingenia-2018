<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $table = 'stories';
    protected $visible = [
        'id', 'picture', 'body', 'created_at',
    ];
    protected $with = [
        'project',
    ];
    
    public function project()
    {
        return $this->belongsTo('App\Model\Project', 'project_id');
    }
}
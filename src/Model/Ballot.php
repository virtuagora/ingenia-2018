<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ballot extends Model
{
    protected $table = 'ballots';
    protected $visible = [
        'id', 'options', 'node_id',
    ];
    protected $casts = [
        'options' => 'array',
    ];

    public function node()
    {
        return $this->belongsTo('App\Model\Node');
    }

    public function votes()
    {
        return $this->belongsToMany('App\Model\User')->withPivot('vote', 'is_public');
    }
}

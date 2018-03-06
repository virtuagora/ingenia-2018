<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;

class Voting extends Node
{
    protected $visible = [
        'id', 'title', 'text', 'points', 'close_date', 'author',
        'created_at', 'ballot',
    ];
    protected $with = [
        'ballot',
    ];
    protected $attributes = [
        'type' => 'Voting',
    ];

    public function ballot()
    {
        return $this->hasOne('App\Mode\Ballot');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('voting', function (Builder $builder) {
            $builder->where('type', 'Voting');
        });
    }
}

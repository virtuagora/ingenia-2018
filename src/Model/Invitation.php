<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $table = 'invitations';
    protected $visible = [
        'id', 'email', 'state', 'user_id', 'group_id',
    ];
    protected $fillable = [
        'user_id', 'group_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Model\Group');
    }
}

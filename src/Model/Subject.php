<?php

namespace App\Model;

use App\Util\DummySubject;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public $timestamps = false;
    protected $table = 'subjects';
    protected $visible = [
        'id', 'display_name', 'img_type', 'img_hash', 'points',
    ];

    public function actor()
    {
        $entity = 'App\\Model\\'.$this->type;
        return $this->hasOne($entity);
    }

    public function groups()
    {
        return $this->belongsToMany('App\Model\Group')->withPivot('relation', 'title');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Model\Role', 'subject_role');
    }

    public function toDummy()
    {
        return new DummySubject(
            $this->type,
            $this->id,
            $this->display_name,
            $this->roles()->pluck('role_id')->all()
        );
    }
}

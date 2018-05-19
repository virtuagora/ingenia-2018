<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $table = 'users';
    protected $dates = ['deleted_at', 'token_expiration'];
    protected $visible = [
        'id', 'names', 'surnames', 'warning', 'ban_exp', 'created_at', 'subject',
        'locality_id', 'locality_other', 'pending_tasks', 'bio', 'pivot'
    ];
    protected $with = ['subject'];
    protected $appends = ['pending_tasks'];

    public function subject()
    {
        return $this->belongsTo('App\Model\Subject');
    }

    public function locality()
    {
        return $this->belongsTo('App\Model\Locality');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Model\Group', 'user_group')->withPivot('relation', 'title');
    }

    public function invitations()
    {
        return $this->hasMany('App\Model\Invitation');
    }

    public function setNamesAttribute($value)
    {
        $this->attributes['names'] = $value;
        if (isset($this->attributes['surnames'])) {
            $fullname = $this->attributes['names'] . ' ' . $this->attributes['surnames'];
            $this->attributes['trace'] = mb_strtolower(trim($fullname));
        } else {
            $this->attributes['trace'] = mb_strtolower(trim($value));
        }
    }

    public function setSurnamesAttribute($value)
    {
        $this->attributes['surnames'] = $value;
        if (isset($this->attributes['names'])) {
            $fullname = $this->attributes['names'] . ' ' . $this->attributes['surnames'];
            $this->attributes['trace'] = mb_strtolower(trim($fullname));
        } else {
            $this->attributes['trace'] = mb_strtolower(trim($value));
        }
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }

    public function getPendingTasksAttribute()
    {
        $tasks = [];
        // if (!isset($this->attributes['email'])) {
        //     $tasks[] = 'email';
        // }
        if (!isset($this->attributes['dni'])) {
            $tasks[] = 'dni';
        }
        if (!isset($this->attributes['birthday'])) {
            $tasks[] = 'profile';
        }
        return $tasks;
    }

    public function getRelationsWith($subject)
    {
        return ($this->subject_id == $subject->getId())? ['self']: [];
    }

    /*public function meta()
    {
        return $this->hasMany('App\Model\UserMeta');
    }*/
}

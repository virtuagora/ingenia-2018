<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $table = 'users';
    protected $dates = ['deleted_at'];
    protected $visible = [
        'id', 'names', 'surnames', 'warning', 'ban_exp', 'created_at', 'subject',
    ];
    protected $with = ['subject'];

    public function subject()
    {
        return $this->belongsTo('App\Model\Subject');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Model\Group')->withPivot('relation', 'title');
    }

    public function setNamesAttribute($value)
    {
        $this->attributes['names'] = $value;
        $fullname = $this->attributes['names'] . ' ' . $this->attributes['surnames'];
        $this->attributes['trace'] = mb_strtolower(trim($fullname));
    }

    public function setSurnamesAttribute($value)
    {
        $this->attributes['surnames'] = $value;
        $fullname = $this->attributes['names'] . ' ' . $this->attributes['surnames'];
        $this->attributes['trace'] = mb_strtolower(trim($fullname));
    }

    public function setPaswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }

    /*public function meta()
    {
        return $this->hasMany('App\Model\UserMeta');
    }*/
}

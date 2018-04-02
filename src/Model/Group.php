<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $table = 'groups';
    protected $dates = ['deleted_at'];
    protected $visible = [
        'id', 'name', 'year', 'description', 'previous_editions',
        'parent_organization', 'web', 'facebook',
        'locality_other', 'locality', 'pivot', 'project',
    ];
    protected $with = [
        'subject',
    ];
    protected $casts = [
        'previous_editions' => 'array',
        'parent_organization' => 'array',
    ];

    public function subject()
    {
        return $this->belongsTo('App\Model\Subject');
    }

    public function project()
    {
        return $this->hasOne('App\Model\Project');
    }

    public function users()
    {
        return $this->belongsToMany('App\Model\User', 'user_group')->withPivot('relation', 'title');
    }

    public function invitations()
    {
        return $this->hasMany('App\Model\Invitation');
    }

    public function getRelationsWith($subject)
    {
        $user = $this->users()->where('subject_id', $subject->getId())->first();
        return isset($user) ? [$user->pivot->relation] : [];
    }
}

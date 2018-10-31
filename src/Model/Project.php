<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';
    protected $dates = ['deleted_at'];
    protected $visible = [
        'id', 'name', 'abstract', 'foundation', 'previous_work',
        'neighbourhoods', 'organization', 'locality_id', 'locality_other',
        'goals', 'schedule', 'budget', 'category_id', 'coordin_id',
        'has_image', 'likes', 'group', 'category',
        'selected_budget', 'granted_budget', 'selected',
        'budget_sent', 'budget_approved'
    ];
    protected $with = [];
    protected $casts = [
        'budget' => 'array',
        'schedule' => 'array',
        'neighbourhoods' => 'array',
        'goals' => 'array',
        'organization' => 'array',
        'selected' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo('App\Model\Group');
    }

    public function category()
    {
        return $this->belongsTo('App\Model\Category');
    }

    public function locality()
    {
        return $this->belongsTo('App\Model\Locality');
    }

    public function coordin()
    {
        return $this->belongsTo('App\Model\User', 'coordin_id');
    }

    public function voters()
    {
        return $this->belongsToMany('App\Model\User', 'project_user', 'project_id', 'user_id');
    }

    public function getRelationsWith($subject)
    {
        $user = $this->group->users()->where('subject_id', $subject->getId())->first();
        return isset($user) ? [$user->pivot->relation] : [];
    }
}

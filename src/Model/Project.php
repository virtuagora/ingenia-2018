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
        'goals', 'schedule', 'budget', 'category_id',
        'has_image', 'likes', 'group', 'category',
    ];
    protected $with = [];
    protected $casts = [
        'budget' => 'array',
        'schedule' => 'array',
        'neighbourhoods' => 'array',
        'goals' => 'array',
        'organization' => 'array',
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

    public function getRelationsWith($subject)
    {
        $user = $this->group->users()->where('subject_id', $subject->getId())->first();
        return isset($user) ? [$user->pivot->relation] : [];
    }
}

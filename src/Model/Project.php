<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'groups';
    protected $dates = ['deleted_at'];
    protected $visible = [
        'id', 'name', 'abstract', 'foundation', 'previous_work', 'neighbourhood',
        'goals', 'budget', 'schedule', 'organization', 'has_image', 'likes',
        'locality_other', 'locality', 'group', 'category',
    ];
    protected $with = [];
    protected $casts = [
        'budget' => 'array',
        'schedule' => 'array',
        'neighbourhoods' => 'array',
        'goals' => 'array',
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
}

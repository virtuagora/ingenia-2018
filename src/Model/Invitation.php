<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $table = 'invitations';
    protected $visible = [
        'id', 'email', 'state', 'user_id', 'group_id', 'comment',
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

    public function getRelationsWith($subject)
    {
        if ($this->state == 'requested') {
            $sameUsr = $this->user()->where('subject_id', $subject->getId())->first();
            if (isset($sameUsr)) {
                return ['sender'];
            } else {
                return in_array('responsable', $this->group->getRelationsWith($subject)) ?
                    ['receiver'] : [];
            }
        } elseif ($this->state == 'pending') {
            $sameUsr = $this->user()->where('subject_id', $subject->getId())->first();
            if (isset($sameUsr)) {
                return ['receiver'];
            } else {
                return in_array('responsable', $this->group->getRelationsWith($subject)) ?
                    ['sender'] : [];
            }
        } else {
            return [];
        }
    }
}

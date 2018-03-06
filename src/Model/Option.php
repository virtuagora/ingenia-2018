<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';
    protected $visible = ['id', 'key', 'velue', 'type', 'group', 'autoload'];
    protected $casts = [
        'autoload' => 'boolean',
    ];
}

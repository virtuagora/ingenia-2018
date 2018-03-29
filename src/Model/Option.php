<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';
    protected $visible = ['id', 'key', 'value', 'type', 'group', 'autoload'];
    protected $casts = [
        'autoload' => 'boolean',
    ];

    public function getValueAttribute($value)
    {
        if ($this->type == 'array') {
            return json_decode($value, true);
        } else {
            return $value;
        }
    }
}

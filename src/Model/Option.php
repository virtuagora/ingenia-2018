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
        // return $this->castAttribute($this->type, $value);
        switch ($this->type) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'boolean':
                return (bool) $value;
            case 'object':
            case 'array':
                return json_decode($value, true);
            case 'date':
                return $this->asDate($value);
            default:
                return $value;
        }
    }
}

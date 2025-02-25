<?php

namespace Saeedvir\LaravelAdvancedAttributes;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $title
 * @property string     $value
 * @property string     $attributable
 * @property string|int $attributable_id
 */
class Attribute extends Model
{
    protected $guarded = [];

    protected $fillable = ['name', 'type', 'unit', 'is_required', 'default_value', 'description'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('laravel-advanced-attributes.tables.attributes', 'attributes');

        parent::__construct($attributes);
    }

    // public function attributables(): MorphToMany
    // {
    //     return $this->morphedByMany(Order::class, 'attributable', 'attributables', 'attribute_id', 'attributable_id')
    //         ->withPivot(['value_int', 'value_decimal', 'value_text', 'value_date', 'value_boolean', 'value_json']);
    // }

    public function getFormattedValue($pivot)
    {
        switch ($this->type) {
            case 'text':
                return $pivot->value_text;
            case 'int':
                return (int) $pivot->value_int;
            case 'decimal':
                return (float) $pivot->value_decimal;
            case 'date':
                return $pivot->value_date;
            case 'boolean':
                return (bool) $pivot->value_boolean;
            case 'json':
                return json_decode($pivot->value_json, true);
            default:
                return null;
        }
    }
}

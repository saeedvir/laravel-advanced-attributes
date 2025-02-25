<?php

namespace Saeedvir\LaravelAdvancedAttributes\Traits;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait HasAttributes
{
    use HasRelationships;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->attributes()->detach();
        });
    }

    /**
     * Get all attributes for the Model.
     */
    public function attributes(): MorphToMany
    {
        return $this->morphToMany(
            config('laravel-advanced-attributes.attributes_model'),
            'attributable',
            config('laravel-advanced-attributes.attributables_model'),
            'attributable_id',
            'attribute_id'
        )->withPivot([
            'value_int',
            'value_decimal',
            'value_text',
            'value_date',
            'value_boolean',
            'value_json'
        ]);
    }
    

    /**
     * Get the value of a specific attribute for the product.
     *
     * @return mixed
     */
    public function getAttributeValueByName(string $attributeName)
    {
        // Find the attribute by name
        $attribute = $this->getCachedAttributes()->where('name', $attributeName)->first();
        // $attribute = $this->attributes()->where('name', $attributeName)->first();

        if (! $attribute) {
            return null; // Attribute not found
        }

        // Determine the value column based on the attribute type
        switch ($attribute->type) {
            case 'integer':
                return $attribute->pivot->value_int ?? $attribute->default_value;
            case 'decimal':
                return $attribute->pivot->value_decimal ?? $attribute->default_value;
            case 'text':
                return $attribute->pivot->value_text ?? $attribute->default_value;
            case 'date':
                return $attribute->pivot->value_date ?? $attribute->default_value;
            case 'boolean':
                return $attribute->pivot->value_boolean ?? $attribute->default_value;
            case 'json':
                return $attribute->pivot->value_json ?? $attribute->default_value;
            default:
                return null; // Unknown type
        }
    }

    public function updateAttributes(array $attributes)
    {
        foreach ($attributes as $attributeName => $value) {
            $attribute = Attribute::where('name', $attributeName)->first();

            if ($attribute) {
                $this->attributes()->updateExistingPivot($attribute->id, [
                    'value_text' => $attribute->type === 'text' ? $value : null,
                    'value_int' => $attribute->type === 'int' ? (int) $value : null,
                    'value_decimal' => $attribute->type === 'decimal' ? (float) $value : null,
                    'value_date' => $attribute->type === 'date' ? $value : null,
                    'value_boolean' => $attribute->type === 'boolean' ? (bool) $value : null,
                ]);
            }
        }
    }

    public function getCachedAttributes()
    {
        return Cache::remember("attributes:{$this->id}", 10, function () {
            return $this->attributes()->get();
        });
    }

    public function generateAttributeReport()
    {
        $attributes = $this->attributes()->get();
        $report = [];

        foreach ($attributes as $attribute) {
            $report[] = [
                'Attribute' => $attribute->name,
                'Value' => $attribute->getFormattedValue($attribute->pivot),
                'Type' => $attribute->type,
            ];
        }

        return $report;
    }

    // // Get only text attributes
    public function textAttributes()
    {
        return $this->attributes()->where('type', 'text');
    }

    // Get only numeric attributes (int or decimal)
    public function numericAttributes()
    {
        return $this->attributes()->whereIn('type', ['int', 'decimal']);
    }

    public function applyDefaultAttributes()
    {
        $missingAttributes = Attribute::whereNotIn('id', $this->attributes()->pluck('attributes.id'))
            ->where('is_required', true)
            ->get();

        foreach ($missingAttributes as $attribute) {
            $this->attributes()->attach($attribute->id, [
                'value_text' => $attribute->type == 'text' ? $attribute->default_value : null,
                'value_int' => $attribute->type == 'int' ? (int) $attribute->default_value : null,
                'value_decimal' => $attribute->type == 'decimal' ? (float) $attribute->default_value : null,
                'value_date' => $attribute->type == 'date' ? $attribute->default_value : null,
                'value_boolean' => $attribute->type == 'boolean' ? (bool) $attribute->default_value : null,
            ]);
        }
    }

    public function generateFormFields()
    {
        // $fields = Product::find(1)->generateFormFields();

        return $this->attributes->map(function ($attribute) {
            return [
                'name' => $attribute->name,
                'type' => match ($attribute->type) {
                    'text' => 'text',
                    'int' => 'number',
                    'decimal' => 'number',
                    'date' => 'date',
                    'boolean' => 'checkbox',
                    default => 'text'
                },
                'required' => $attribute->is_required,
                'default' => $attribute->default_value,
            ];
        });
    }

    /**
     * Scope to filter Model by a specific attribute and value.
     *
     * @param  mixed  $value
     */
    public function scopeFilterByAttribute(Builder $query, string $attributeName, $value): Builder
    {
        return $query->whereHas('attributes', function ($q) use ($attributeName, $value) {
            $q->where('name', $attributeName)
                ->where(function ($q) use ($value) {
                    $q->where('value_text', $value)
                        ->orWhere('value_int', $value)
                        ->orWhere('value_decimal', $value)
                        ->orWhere('value_date', $value)
                        ->orWhere('value_boolean', $value);
                });
        });
    }

    /**
     * Scope to filter Model by multiple attributes and their values.
     *
     * @param  Builder  $query
     * @param  array  $conditions
     * @return Builder
     */
    public function scopeFilterByAttributes($query, array $filters)
    {
        foreach ($filters as $attributeName => $value) {
            $query->whereHas('attributes', function ($q) use ($attributeName, $value) {
                $q->where('name', $attributeName)
                    ->where(function ($q) use ($value) {
                        $q->where('value_text', $value)
                            ->orWhere('value_int', $value)
                            ->orWhere('value_decimal', $value)
                            ->orWhere('value_date', $value)
                            ->orWhere('value_boolean', $value);
                    });
            });
        }

        return $query;
    }

    /**
     * Scope to filter Model by a specific date attribute.
     *
     * @param  Carbon|string  $date
     */
    public function scopeFilterDateAttribute(Builder $query, string $attributeName, $date): Builder
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $query->whereHas('attributes', function ($q) use ($attributeName, $date) {
            $q->where('name', $attributeName)
                ->whereDate('value_date', $date->toDateString());
        });
    }

    /**
     * Scope to filter Model by a specific boolean attribute.
     */
    public function scopeFilterBooleanAttribute(Builder $query, string $attributeName, bool $value): Builder
    {
        return $query->whereHas('attributes', function ($q) use ($attributeName, $value) {
            $q->where('name', $attributeName)
                ->where('value_boolean', $value);
        });
    }

    /**
     * Scope to filter Model by a specific JSON attribute.
     *
     * @param  mixed  $value
     */
    public function scopeFilterJsonAttribute(Builder $query, string $attributeName, $value): Builder
    {
        return $query->whereHas('attributes', function ($q) use ($attributeName, $value) {
            $q->where('name', $attributeName)
                ->whereJsonContains('value_json', $value);
        });
    }

    public function scopeSearchAttributes($query, $keyword)
    {
        return $query->whereHas('attributes', function ($q) use ($keyword) {
            $q->where('value_text', 'LIKE', "%{$keyword}%");
        });
    }
}

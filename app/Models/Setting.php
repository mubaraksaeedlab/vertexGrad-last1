<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'label',
        'value',
        'type',
        'description',
        'is_public',
        'options',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'options'   => 'array',
    ];

    public function getCastedValueAttribute(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'number'  => is_numeric($this->value) ? $this->value + 0 : $this->value,
            'json'    => $this->value ? json_decode($this->value, true) : [],
            default   => $this->value,
        };
    }
}
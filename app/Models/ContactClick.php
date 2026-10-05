<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactClick extends Model
{
    public const ATTRIBUTION_FIELDS = [
        'gclid', 'gbraid', 'wbraid', 'utm_source', 'utm_medium',
        'utm_campaign', 'utm_term', 'utm_content',
    ];

    protected $fillable = [
        'ref', 'channel', 'variant', 'page_path', 'qualified_at',
        ...self::ATTRIBUTION_FIELDS,
    ];

    protected function casts(): array
    {
        return [
            'qualified_at' => 'datetime',
        ];
    }
}

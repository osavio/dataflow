<?php

namespace App\Models;

use App\Enums\ImportItemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportItem extends Model
{
    protected $fillable = [
        'import_id',
        'data',
        'status',
        'error_message'
    ];

    protected $attributes = [
        'status' => ImportItemStatus::Pending->value,
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'status' => ImportItemStatus::class,
        ];
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}

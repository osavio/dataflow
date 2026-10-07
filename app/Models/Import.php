<?php

namespace App\Models;

use App\Enums\ImportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    protected $fillabel = [
        'user_id',
        'file_path',
        'status',
        'error_message',
    ];

    protected $attributes = [
        'status' => ImportStatus::Pending->value,
    ];

    protected function casts(): array
    {
        return [
            'status' => ImportStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ImportItem::class);
    }
}

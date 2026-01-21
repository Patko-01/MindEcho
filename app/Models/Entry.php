<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail(mixed $entry_id)
 * @method static create(array $array)
 * @method static where(string $string, mixed $entry_id)
 */
class Entry extends Model
{
    protected $fillable = ['user_id', 'entry_title', 'tag'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function response(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}

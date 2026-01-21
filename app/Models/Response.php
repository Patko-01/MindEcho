<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 */
class Response extends Model
{
    protected $fillable = ['entry_id', 'model_name', 'content'];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}

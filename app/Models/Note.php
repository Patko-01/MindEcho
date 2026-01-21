<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = ['entry_id', 'content'];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}

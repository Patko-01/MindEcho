<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiQuestion extends Model
{
    protected $fillable = ['ai_question'];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}

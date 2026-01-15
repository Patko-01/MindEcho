<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static pluck(string $string)
 * @method static where(string $string, mixed $model)
 * @method static create(array $array)
 */
class Models extends Model
{
    protected $fillable = ['name', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}

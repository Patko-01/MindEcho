<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(mixed $modelId)
 * @method static firstOrCreate(string[] $array, string[] $array1)
 * @method static orderBy(string $string)
 * @method static pluck(string $string)
 * @method static updateOrCreate(string[] $array, array $array1)
 * @method static whereIn(string $string, $currentIds)
 * @method static where(string $string, string[] $array)
 */
class AiModel extends Model
{
    protected $fillable = ['name', 'description', 'status'];
}

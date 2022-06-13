<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property int $id
 * @property string $uuid
 *
 * @mixin Illuminate\Database\Eloquent\Builder
 */
class Roulotte extends Model
{
    use HasFactory;

    protected static $unguarded = true;

    protected static function newFactory(): RoulotteFactory
    {
        return new RoulotteFactory();
    }
}

<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $name
 *
 * @mixin Illuminate\Database\Eloquent\Builder
 */
class Car extends Model
{
    use HasFactory;

    protected static $unguarded = true;

    protected static function newFactory(): CarFactory
    {
        return new CarFactory();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

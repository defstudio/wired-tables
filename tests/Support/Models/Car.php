<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    protected static function newFactory(): CarFactory
    {
        return new CarFactory();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
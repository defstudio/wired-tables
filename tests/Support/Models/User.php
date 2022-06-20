<?php


/**
 * @property string $name
 *
 * @mixin Illuminate\Database\Eloquent\Builder
 */
class User extends \Illuminate\Foundation\Auth\User
{
    protected static $unguarded = true;
}

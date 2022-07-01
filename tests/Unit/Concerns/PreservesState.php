<?php

use DefStudio\WiredTables\Concerns\PreservesState;
use function Pest\Laravel\actingAs;

it('can generate a state key', function () {
    $class = new class () {
        use PreservesState;
        protected string $slug = '';
    };
    $class->mountPreservesState();

    $key = Livewire\invade($class)->getStateKey(new User(['id' => 42]), 'baz');

    expect($key)->toBe('httplocalhost-42-state-baz');
});

it('can store and retrieve state', function () {
    $class = new class () {
        use PreservesState;
        protected string $slug = '';

        protected function config($key): bool
        {
            return true;
        }
    };
    $class->mountPreservesState();

    actingAs(new User(['id' => 42]));

    Livewire\invade($class)->storeState('foo', 666);

    $cached = Livewire\invade($class)->getState('foo');

    expect($cached)->toBe(666);
});

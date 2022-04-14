<?php

use DefStudio\WiredTables\Enums\Sorting;

it('can return the next element', function (Sorting $current, Sorting $next) {
    expect($current->next())->toBe($next);
})->with([
    'none' => ['current' => Sorting::none, 'next' => Sorting::asc],
    'asc' => ['current' => Sorting::asc, 'next' => Sorting::desc],
    'desc' => ['current' => Sorting::desc, 'next' => Sorting::none],
]);

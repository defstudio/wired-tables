<?php

/** @noinspection SqlDialectInspection */

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\WiredTables\Elements\Filter;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\ColumnException;
use Illuminate\Database\Eloquent\Builder;

test('defaults', function () {
    $filter = new Filter(fakeTable(), 'Test');

    expect($filter->toArray())->toBe([
        'type' => 'text',
        'name' => 'Test',
        'key' => 'test',
        'active' => false,
        'value' => null,
    ]);
});

it('can return its name', function () {
    $filter = new Filter(fakeTable(), 'Test');

    expect($filter->name())->toBe('Test');
});

it('can return its key', function () {
    $filter = new Filter(fakeTable(), 'Test', 'my_filter');

    expect($filter->key())->toBe('my_filter');
});

it('can return its default key', function () {
    $filter = new Filter(fakeTable(), 'A Test');

    expect($filter->key())->toBe('a_test');
});

test('its type can be set to "select"', function () {
    $filter = new Filter(fakeTable(), 'A Test');
    $filter->select(['a' => 'foo'])->placeholder('please, select');

    expect($filter)
        ->type()->toBe('select')
        ->get(Config::options)->toBe(['a' => 'foo'])
        ->get(Config::placeholder)->toBe('please, select');
});

test('its type can be set to date', function () {
    $filter = new Filter(fakeTable(), 'A Test');
    $filter->date();

    expect($filter)
        ->type()->toBe('date');
});

test('its type can be set to checkbox', function () {
    $filter = new Filter(fakeTable(), 'A Test');
    $filter->checkbox();

    expect($filter)
        ->type()->toBe('checkbox');
});

it('can be hidden', function () {
    $filter = new Filter(fakeTable(), 'A Test');
    $filter->hidden();

    expect($filter->get(Config::hidden))->toBeTrue();
});

it('can be hidden through a closure', function () {
    $filter = new Filter(fakeTable(), 'A Test');
    $filter->hidden(fn () => true);

    expect($filter->get(Config::hidden))->toBeCallable();
});

it('can tell if it is visible', function () {
    $filter = new Filter(fakeTable(), 'A Test');

    $filter->hidden();
    expect($filter->isVisible())->toBeFalse();

    $filter->hidden(fn () => true);
    expect($filter->isVisible())->toBeFalse();

    $filter->hidden(false);
    expect($filter->isVisible())->toBeTrue();
});

it('can tell if it is active', function () {
    $table = fakeTable();
    $filter = new Filter($table, 'A Test');

    expect($filter->isActive())->toBeFalse();

    $table->filterValues['a_test'] = 42;

    expect($filter->isActive())->toBeTrue();
});

it('can be configured to be displayed on a column', function () {
    $filter = new Filter(fakeTable(), 'Owner');
    expect($filter->isColumnFilter())->toBeFalse();

    $filter->displayOnColumn();

    expect($filter->isColumnFilter())->toBeTrue();
});

it('should be named after a column to be associated with it', function () {
    $filter = new Filter(fakeTable(), 'Foo');

    expect(fn () => $filter->displayOnColumn())
        ->toThrow(ColumnException::class);
});

it('can return its type', function () {
    $filter = new Filter(fakeTable(), 'Foo');

    expect($filter->type())->toBe('text');
});

it('can return its value', function () {
    $table = fakeTable();
    $filter = new Filter($table, 'Foo');

    $table->filterValues['foo'] = 666;

    expect($filter->value())->toBe(666);
});

it('can return its formatted value', function () {
    $table = fakeTable();
    $filter = new Filter($table, 'Foo');

    $table->filterValues['foo'] = 'bar';
    expect($filter->formattedValue())->toBe('bar');

    $filter->select(['bar' => 'label']);
    expect($filter->formattedValue())->toBe('label');

    $table->filterValues['foo'] = '2022-04-21';
    $filter->date();
    expect($filter->formattedValue())->toBe('21/04/2022');
});

it('can set its handler', function () {
    $table = fakeTable();
    $filter = new Filter($table, 'Foo');

    $filter->handle(fn () => null);

    expect($filter->get(Config::handler))
        ->toBeCallable();
});

it('can be applied to a query', function () {
    $table = fakeTable();
    $filter = new Filter($table, 'Name');

    $filter->handle(function (Builder $query) {
        $query->where('foo', 'bar');
    });

    $query = Car::query();
    $filter->apply($query);

    enableDebug();
    expect($table->debugQuery($query))
        ->toBe('select * from "cars" where "foo" = \'bar\'');
});

it('can apply a text search query', function () {
    $table = fakeTable();
    $filter = new Filter($table, 'Name');

    $table->filterValues['name'] = 'foo';

    $query = Car::query();
    $filter->apply($query);

    enableDebug();
    expect($table->debugQuery($query))
        ->toBe('select * from "cars" where ("name" like \'%foo%\')');
});

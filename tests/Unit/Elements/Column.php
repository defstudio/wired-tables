<?php

/** @noinspection SqlDialectInspection */

use DefStudio\WiredTables\Elements\Column;
use DefStudio\WiredTables\Enums\ColumnType;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Enums\Sorting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

use function Spatie\Snapshots\assertMatchesSnapshot;

test('defaults', function () {
    $table = fakeTable();
    $table->id = 1234;
    $column = new Column($table, "Test");


    expect($column->toArray())->toBe([
        'is_sortable' => false,
        'is_searchable' => false,
        'name' => 'Test',
        'db_column' => 'test',
        'id' => 'e562ec174202bbd67b8ba02e26e57dc9',
        'field' => 'test',
        'is_relation' => false,
        'relation' => '',
    ]);
});

it('can set its current model', function () {
    $table = fakeTable();
    $column = new Column($table, "Name");

    $column->setModel(Car::make(['name' => 'foo']));

    expect($column->value())->toBe("foo");
});

it('can return its name', function () {
    $column = new Column(fakeTable(), "Foo");

    expect($column->name())->toBe("Foo");
});

it('can return its db column', function () {
    $column = new Column(fakeTable(), "Foo", 'model.field');

    expect($column->dbColumn())->toBe("model.field");
});

it('can compute its db column from the name', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    expect($column->dbColumn())->toBe("foo_bar");
});

it('can return its id', function () {
    $table = fakeTable();
    $table->id = 1234;
    $column = new Column($table, "Test");


    expect($column->id())->toBe('e562ec174202bbd67b8ba02e26e57dc9');
});

it('can be set as sortable', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    expect($column->get(Config::is_sortable))->toBeFalse();

    $column->sortable();

    expect($column->get(Config::is_sortable))->toBeTrue();
});

it('can be set as wrappable', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    expect($column->get(Config::wrapText))->toBeNull();

    $column->wrapText();

    expect($column->get(Config::wrapText))->toBeTrue();
});

it('can be set as sortable through a closure', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    $column->sortable(function (Builder $query) {
    });

    expect($column->get(Config::sort_closure))->toBeCallable();
});

it('can check if it is sortable', function () {
    $column = new Column(fakeTable(), "Foo Bar");
    $column->sortable();

    expect($column->isSortable())->toBeTrue();
});

it('can check if it has a sort closure', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    expect($column->hasSortClosure())->toBeFalse();

    $column->sortable(function (Builder $query) {
    });

    expect($column->hasSortClosure())->toBeTrue();
});

it('can apply its sort closure', function () {
    $applied = false;

    $column = new Column(fakeTable(), "Foo Bar");
    $column->sortable(function (Builder $query, Sorting $dir) use (&$applied) {
        expect($query)->toBeInstanceOf(Builder::class);
        expect($dir)->toBe(Sorting::asc);
        $applied = true;
    });

    $column->applySortClosure(Car::query(), Sorting::asc);

    expect($applied)->toBeTrue();
});

it('can format its value', function () {
    $column = new Column(fakeTable(), "Name");
    $column->format(function (Column $column) {
        return strtoupper($column->value());
    });

    expect($column->get(Config::format_closure))->toBeCallable();
});

it('can return its value', function () {
    $column = new Column(fakeTable(), "Name");
    $column->format(function (Column $column) {
        return strtoupper($column->value());
    });

    $column->setModel(Car::make(['name' => 'foo']));

    expect($column->value())->toBe('foo');
});

it('can return its value from a json column', function () {
    $column = new Column(fakeTable(), "Foo", 'data->foo');
    $column->format(function (Column $column) {
        return strtoupper($column->value());
    });

    $column->setModel(Car::make(['name' => 'car', 'data' => ['foo' => 'baz']]));

    expect($column->value())->toBe('baz');
});

it('can return a relationship value', function () {
    $column = new Column(fakeTable(), "Owner", 'owner.name');

    $relationship = User::make(['name' => 'foo']);

    $model = Car::make(['name' => 'bar']);
    $model->setRelation('owner', $relationship);

    $column->setModel($model);

    expect($column->value())->toBe('foo');
});

it('can render a formatted value', function () {
    $column = new Column(fakeTable(), "Name");
    $column->format(function (string $name, Car $car) {
        expect($name)->toBe('foo');

        return strtoupper($car->name);
    });


    $column->setModel(Car::make(['name' => 'foo']));

    expect($column->render()->toHtml())->toBe('FOO');
});

it('can render a view', function () {
    $table = fakeTable();
    $column = new Column($table, "test", 'name');
    $column->view('foo.bar', ['baz' => 'quuz']);

    $car = new Car(['name' => 'bmw']);
    $column->setModel($car);


    Blade::shouldReceive('render')
        ->with('foo.bar', [
            'model' => $car,
            'value' => $car->name,
            'column' => $column,
            'baz' => 'quuz',
        ])
        ->once()
        ->andReturn('rendered');

    expect($column->render())
        ->toBeInstanceOf(HtmlString::class)
        ->toHtml()->toBe('rendered');
});

it('can render a carbon value', function () {
    $column = new Column(fakeTable(), "Created", 'created_at');
    $column->carbon('d/m/Y');

    Carbon::setTestNow('1985-07-17 11:55:00');

    $model = new Car(['name' => 'bar', 'created_at' => now()]);
    $column->setModel($model);



    expect($column->render()->toHtml())->toBe('17/07/1985');
});

it('can render a boolean value', function () {
    $column = new Column(fakeTable(), "Broken");
    $column->boolean();

    $model = new Car(['name' => 'bar', 'broken' => false]);
    $column->setModel($model);

    assertMatchesSnapshot($column->render()->toHtml());

    $model = new Car(['name' => 'bar', 'broken' => true]);
    $column->setModel($model);

    assertMatchesSnapshot($column->render()->toHtml());
})->skip(PHP_OS_FAMILY === "Windows");

it('can check if it is a relation column', function () {
    $column = new Column(fakeTable(), "Name");
    expect($column->isRelation())->toBeFalse();

    $column = new Column(fakeTable(), "Test", 'data->foo.bar');
    expect($column->isRelation())->toBeFalse();

    $column = new Column(fakeTable(), "Owner", 'owner.name');
    expect($column->isRelation())->toBeTrue();
});

it('can check if it is a json column', function () {
    $column = new Column(fakeTable(), "Name");
    expect($column->isJson())->toBeFalse();

    $column = new Column(fakeTable(), "Test", 'data->foo->bar');
    expect($column->isJson())->toBeTrue();

    $column = new Column(fakeTable(), "Owner", 'owner.name->foo');
    expect($column->isJson())->toBeFalse();
});

it('can return its relation', function () {
    $column = new Column(fakeTable(), "Owner", 'owner.name');
    expect($column->getRelation())->toBe('owner');

    $column = new Column(fakeTable(), "Owner", 'owner.wife.name');
    expect($column->getRelation())->toBe('owner.wife');
});

it('can return its field', function () {
    $column = new Column(fakeTable(), "Name", 'name');
    expect($column->getField())->toBe('name');

    $column = new Column(fakeTable(), "Owner", 'owner.name');
    expect($column->getField())->toBe('name');

    $column = new Column(fakeTable(), "Owner", 'data->foo');
    expect($column->getField())->toBe('data->foo');

    $column = new Column(fakeTable(), "Owner", 'owner.wife.name');
    expect($column->getField())->toBe('name');
});

it('can be set as searchable', function () {
    $column = new Column(fakeTable(), "Test");

    expect($column->get(Config::is_searchable))->toBeFalse();

    $column->searchable();

    expect($column->get(Config::is_searchable))->toBeTrue();
});

it('can be set as searchable through a closure', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    $column->searchable(function (Builder $query) {
    });

    expect($column->get(Config::search_closure))->toBeCallable();
});

it('can check if it is searchable', function () {
    $column = new Column(fakeTable(), "Foo Bar");
    $column->searchable();

    expect($column->isSearchable())->toBeTrue();
});

it('can check if it has a search closure', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    expect($column->hasSearchClosure())->toBeFalse();

    $column->searchable(function (Builder $query, string $term) {
    });

    expect($column->hasSearchClosure())->toBeTrue();
});

it('can apply its search closure', function () {
    $applied = false;

    $column = new Column(fakeTable(), "Foo Bar");
    $column->searchable(function (Builder $query, string $term) use (&$applied) {
        expect($query)->toBeInstanceOf(Builder::class);
        expect($term)->toBe('quuz');
        $applied = true;
    });

    $column->applySearchClosure(Car::query(), 'quuz');

    expect($applied)->toBeTrue();
});

it('can retrieve its relation nesting', function () {
    $column = new Column(fakeTable(), 'Name', 'foo.bar.baz.name');

    expect($column->getRelationNesting())->toBe(3);
});


it('can set a column type to boolean', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    expect($column->get(Config::type))->toBeNull();

    $column->boolean();

    expect($column->get(Config::type))->toBe(ColumnType::boolean);
});

it('can set a column type to carbon', function () {
    $column = new Column(fakeTable(), "Foo Bar");

    expect($column->get(Config::type))->toBeNull();

    $column->carbon('m/Y');

    expect($column)
        ->get(Config::type)->toBe(ColumnType::carbon)
        ->get(Config::date_format)->toBe('m/Y');
});


it('can have an associated url', function () {
    $column = new Column(fakeTable(), 'Test');

    expect($column)
       ->get(Config::url)->toBeNull()
       ->get(Config::url_target)->toBeNull();

    $column->url(fn ($value) => "http://test.$value.dev", '_blank');

    expect($column)
        ->get(Config::url)->toBeCallable()
        ->get(Config::url_target)->toBe('_blank');
});

it('can retrieve a column url', function () {
    $column = new Column(fakeTable(), 'Name');
    $column->url(fn ($value) => "http://test.$value.dev", '_blank');

    $column->setModel(new Car(['name' => 'foo']));

    expect($column->getUrl())->toBe("http://test.foo.dev");
});

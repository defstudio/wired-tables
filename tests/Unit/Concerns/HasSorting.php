<?php /** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection MultipleExpectChainableInspection */

/** @noinspection SqlRedundantOrderingDirection */

/** @noinspection SqlResolve */

use DefStudio\WiredTables\Enums\Sorting;
use DefStudio\WiredTables\Exceptions\SortingException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\actingAs;

test('cached sorting is mounted', function () {
    actingAs(new User(['id' => 42]));
    Cache::put("httplocalhost-42-state-sorting", [
        'foo' => 'asc',
    ]);
    $table = fakeTable();

    expect($table->sorting)->toBe([
        'foo' => 'asc',
    ]);
});

test('cached sorting can be overridden by a query string', function () {
    actingAs(new User(['id' => 42]));
    Cache::put("httplocalhost-42-state-sorting", ['foo' => 'asc']);
    $table = fakeTable();
    $table->sorting = ['foo' => 'desc'];

    $table->bootedHasSorting();

    expect($table->sorting)->toBe(['foo' => 'desc']);

    expect(Cache::get('httplocalhost-42-state-sorting'))->toBe(['foo' => 'desc']);
});

test('cached sorting is updated when sorting changes', function () {
    actingAs(new User(['id' => 42]));
    $table = fakeTable();
    $table->sorting = ['Name' => 'asc'];

    $table->sort('Name');

    expect(Cache::get('httplocalhost-42-state-sorting'))->toBe(['Name' => 'desc']);
});

test('cached sorting is cleared along with sorting', function () {
    actingAs(new User(['id' => 42]));
    Cache::put("httplocalhost-42-state-sorting", ['foo' => 'asc']);
    $table = fakeTable();

    $table->clearSorting('foo');

    expect(Cache::get('httplocalhost-42-state-sorting'))->toBe([]);
});

test("cached sorting is cleared if column doesn't exist", function () {
    actingAs(new User(['id' => 42]));
    Cache::put("httplocalhost-42-state-sorting", ['foo' => 'asc']);

    $table = fakeTable();
    invade($table)->applySorting(Car::query());

    expect( Cache::get("httplocalhost-42-state-sorting"))->toBe([]);
});

test("cached sorting is cleared if column is not sortable", function () {
    actingAs(new User(['id' => 42]));
    Cache::put("httplocalhost-42-state-sorting", ['Not Sortable' => 'asc']);

    $table = fakeTable();

    try{
        invade($table)->applySorting(Car::query());
    }catch (SortingException ){}


    expect( Cache::get("httplocalhost-42-state-sorting"))->toBe([]);
});

it('tells if multiple sorting is enabled', function () {
    $table = fakeTable();

    expect($table->supportMultipleSorting())->toBeFalse();

    $table->configuration()->multipleSorting();

    expect($table->supportMultipleSorting())->toBeTrue();
});

it("doesn't apply sorting to non sortable columns", function () {
    $table = fakeTable();

    expect(fn () => $table->sort('Not Sortable'))
        ->toThrow(SortingException::class);
});

it('can sort a column', function () {
    $table = fakeTable();

    $table->sort('Name');
    expect($table)->rawQuery()->toContain('order by "name" asc');

    $table->sort('Name');
    expect($table)->rawQuery()->toContain('order by "name" desc');

    $table->sort('Name');
    expect($table)->rawQuery()->not->toContain('order by "name"');
});

it('can sort two columns', function () {
    $table = fakeTable();
    $table->configuration()->multipleSorting();

    $table->sort('Name');
    expect($table->sorting)->toBe([
        'Name' => 'asc',
    ]);


    $table->sort('Owner');
    expect($table->sorting)->toBe([
        'Name' => 'asc',
        'Owner' => 'asc',
    ]);

    $table->sort('Name');
    expect($table->sorting)->toBe([
        'Name' => 'desc',
        'Owner' => 'asc',
    ]);

    $table->sort('Name');
    expect($table->sorting)->toBe([
        'Owner' => 'asc',
    ]);

    $table->sort('Name');
    expect($table->sorting)->toBe([
        'Owner' => 'asc',
        'Name' => 'asc',
    ]);
});

it('can sort by a morph relation if there are no related records', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Trailable Name', 'trailable.name')->sortable();
        }
    });

    $table->sort("Trailable Name");

    expect($table)->rawQuery()->toBe('select * from "cars" limit 10 offset 0');
});

it('can sort by a morph relation with related records', function () {
    $car_1 = Car::factory()->create();
    $car_2 = Car::factory()->create();
    Car::factory()->create();

    $car_1->trailable()->associate(Trailer::factory()->create())->save();
    $car_2->trailable()->associate(Roulotte::factory()->create())->save();

    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Trailable Name', 'trailable.name')->sortable();
        }
    });

    $table->sort("Trailable Name");

    expect($table)->rawQuery()->toBe('select * from "cars" order by (select * from (select "name" from "trailers" where "cars"."trailable_type" = \'Trailer\' and "id" = "cars"."trailable_id") union select * from (select "name" from "roulottes" where "cars"."trailable_type" = \'Roulotte\' and "id" = "cars"."trailable_id")) asc limit 10 offset 0');
});

it('returns a column sort direction', function () {
    $table = fakeTable();
    $table->configuration()->multipleSorting();

    $table->sort('Name');
    expect($table->getSortDirection('Name'))->toBe(Sorting::asc);

    $table->sort('Name');
    expect($table->getSortDirection('Name'))->toBe(Sorting::desc);

    $table->sort('Name');
    expect($table->getSortDirection('Name'))->toBe(Sorting::none);
});

it('returns a column sort position', function () {
    $table = fakeTable();
    $table->configuration()->multipleSorting();

    $table->sort('Owner');
    $table->sort('Name');

    expect($table->getSortPosition('Name'))->toBe(2);
    expect($table->getSortPosition('Owner'))->toBe(1);
});

it('applies closure sorting', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('Name');
            $this->column('Owner', 'owner.name')
                ->sortable(function (Builder $query, $dir) {
                    $query->orderBy('foo', $dir->value);
                });
        }
    });

    $table->sort('Owner');
    expect($table)->rawQuery()->toBe('select * from "cars" order by "foo" asc limit 10 offset 0');
});

it('sorts first level relationships', function () {
    $table = fakeTable();

    $table->sort('Owner');
    expect($table)->rawQuery()->toBe('select * from "cars" order by (select "name" from "users" where "cars"."user_id" = "users"."id") asc limit 10 offset 0');
});

it('sorts by field', function () {
    $table = fakeTable();

    $table->sort('Name');
    expect($table)->rawQuery()->toBe('select * from "cars" order by "name" asc limit 10 offset 0');
});

it('can clear a sorting', function () {
    $table = fakeTable();
    $table->sorting['Name'] = 'desc';

    $table->clearSorting('Name');

    expect($table->sorting)->toBeEmpty();
});

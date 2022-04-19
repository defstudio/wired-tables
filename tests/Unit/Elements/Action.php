<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpMultipleClassDeclarationsInspection */

use DefStudio\WiredTables\Elements\Action;
use DefStudio\WiredTables\Enums\Config;
use DefStudio\WiredTables\Exceptions\ActionException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

test('defaults', function () {
    $action = new Action(fakeTable(), 'action one');

    expect($action->toArray())->toBe([
        'with_row_selection' => false,
        'name' => 'action one',
        'method' => 'actionOne',
    ]);
});

it('can enable row selection', function () {
    $action = new Action(fakeTable(), 'foo');
    $action->withRowSelection();

    expect($action->get(Config::with_row_selection))->toBeTrue();
});

it('can tell if row selection is required', function () {
    $action = new Action(fakeTable(), 'foo');
    $action->withRowSelection();

    expect($action->requiresRowsSelection())->toBeTrue();
});

it('can be hidden', function () {
    $action = new Action(fakeTable(), 'foo');
    $action->hidden();

    expect($action->get(Config::hidden))->toBeTrue();
});

it('can be hidden with a closure', function () {
    $action = new Action(fakeTable(), 'foo');
    $action->hidden(fn () => true);

    expect($action->get(Config::hidden))->toBeCallable();
});

it('can set its handler', function () {
    $action = new Action(fakeTable(), 'foo');
    $action->handle(fn () => null);

    expect($action->get(Config::handler))->toBeCallable();
});

it('can return its name', function () {
    $action = new Action(fakeTable(), 'foo');

    expect($action->name())->toBe('foo');
});

it('can return its method arguments', function () {
    $action = new Action(fakeTable(), 'foo');
    $action->handle(fn () => null);

    expect($action->methodArguments()->toArray())->toBe([
        'foo',
    ]);
});

it('can compute its method', function (Action $action, string $method) {
    expect($action->method())->toBe($method);
})->with([
    'closure' => [
        'action' => fn () => (new Action(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('test');
            }
        }, 'my action'))->handle(fn () => null),
        'method' => 'handleAction',
    ],
    'method given' => [
        'action' => fn () => (new Action(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('test');
            }

            public function test(): void
            {
            }
        }, 'my action', 'test')),
        'method' => 'test',
    ],
    'camel' => [
        'action' => fn () => (new Action(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('test');
            }

            public function myAction(): void
            {
            }
        }, 'my action')),
        'method' => 'myAction',
    ],
    'snake' => [
        'action' => fn () => (new Action(new class () extends WiredTable {
            protected function query(): Builder|Relation
            {
                return Car::query();
            }

            protected function columns(): void
            {
                $this->column('test');
            }

            public function my_action(): void
            {
            }
        }, 'my action')),
        'method' => 'my_action',
    ],
]);

it('throws an exception if no method is found', function () {
    $action = new Action(fakeTable(), 'my action');

    expect($action->method(...))->toThrow(ActionException::class);
});

it('can process its handler', function () {
    $processed = false;

    $action = new Action(fakeTable(), 'my action');
    $action->handle(function () use (&$processed) {
        $processed = true;
    });

    $action->processHandler();

    expect($processed)->toBeTrue();
});

test("visibility", function (Action $action, bool $visible) {
    expect($action->isVisible())->toBe($visible);
})->with([
    'hidden by configuration' => [
        'action' => fn () => (new Action(fakeTable(), 'my action'))->hidden(),
        'visible' => false,
    ],
    'hidden by closure' => [
        'action' => fn () => (new Action(fakeTable(), 'my action'))->hidden(fn () => true),
        'visible' => false,
    ],
    'visible by table configuration (row selection not needed)' => [
        'action' => function () {
            $table = fakeTable();
            $table->configuration()->alwaysShowActions();
            $action = new Action($table, 'my action');

            return $action;
        },
        'visible' => true,
    ],
    'visible by table configuration (row selection required)' => [
        'action' => function () {
            $table = fakeTable();
            $table->configuration()->alwaysShowActions();
            $action = new Action($table, 'my action');
            $action->requiresRowsSelection();

            return $action;
        },
        'visible' => true,
    ],
    'visible if row selection is not needed' => [
        'action' => fn () => new Action(fakeTable(), 'my action'),
        'visible' => true,
    ],
    'visible if rows are selected' => [
        'action' => function () {
            $table = fakeTable();
            $table->selectRows([1]);
            $action = new Action($table, 'my action');
            $action->requiresRowsSelection();

            return $action;
        },
        'visible' => true,
    ],
    'hidden if no rows are selected and row selection is required' => [
        'action' => fn () => (new Action(fakeTable(), 'my action'))->withRowSelection(),
        'visible' => false,
    ],
]);

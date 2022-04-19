<?php

/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection PhpUndefinedMethodInspection */

use DefStudio\WiredTables\Elements\Action;
use DefStudio\WiredTables\Exceptions\ActionException;
use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

test('actions are booted', function () {
    $table = fakeTable();

    expect($table->actions)
        ->toBeArray()
        ->not->toBeEmpty();
});

it('can return actions property', function () {
    $table = fakeTable();

    expect($table->actions)->sequence(
        fn ($action) => $action->name()->toBe("action one")
    );
});

test("actions can be defined only inside [->actions()] method", function () {
    $table = new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        public function addAction()
        {
            $this->action('test');
        }

        protected function columns(): void
        {
            $this->column('name');
        }
    };

    expect(fn () => $table->addAction())
        ->toThrow(ActionException::class);
});

it('can find an action by name', function () {
    $table = fakeTable();

    expect($table->getAction('action one'))
        ->toBeInstanceOf(Action::class)
        ->name()->toBe('action one');
});

it('can check if it has actions', function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        public function addAction()
        {
            $this->action('test');
        }

        protected function columns(): void
        {
            $this->column('name');
        }
    });

    expect($table->hasActions())->toBeFalse();

    $table = fakeTable();
    expect($table->hasActions())->toBeTrue();
});

it('can handle an action', function () {
    $table = fakeTable(new class () extends WiredTable {
        public bool $handled = false;

        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('test');
        }

        protected function actions(): void
        {
            $this->action('test')->handle(function () {
                $this->handled = true;
            });
        }
    });

    $table->handleAction('test');

    expect($table->handled)->toBeTrue();
});


it("shouldn't show action selector when there are no actions", function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('test');
        }

        protected function actions(): void
        {
        }
    });

    expect($table->shouldShowActionsSelector())->toBeFalse();
});

it("shouldn't show action selector when no action requires it", function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('test');
        }

        protected function actions(): void
        {
            $this->action('foo')->withRowSelection();
            $this->action('bar')->withRowSelection();
        }
    });

    expect($table->shouldShowActionsSelector())->toBeFalse();
});

it("should show action selector when at least an action requires it", function () {
    $table = fakeTable(new class () extends WiredTable {
        protected function query(): Builder|Relation
        {
            return Car::query();
        }

        protected function columns(): void
        {
            $this->column('test');
        }

        protected function actions(): void
        {
            $this->action('foo')->withRowSelection();
            $this->action('bar');
        }
    });

    expect($table->shouldShowActionsSelector())->toBeTrue();
});

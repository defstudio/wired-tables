<?php

use DefStudio\WiredTables\WiredTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

it('renders the right view', function(){
    $table = new class extends WiredTable{
        protected function query(): Builder|Relation
        {
            return new Builder();
        }

        protected function columns(): void
        {
        }
    };

    expect($table->render())
        ->toBeInstanceOf(\Illuminate\View\View::class)
        ->name()->toBe('wired-tables::main');
});

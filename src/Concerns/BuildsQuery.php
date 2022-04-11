<?php

namespace DefStudio\WiredTables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

trait BuildsQuery
{
    private Builder|Relation $_query;

    public function bootBuildsQuery(): void
    {
        $this->_query = $this->query();
    }

    abstract protected function query(): Builder|Relation;
}

<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Action;
use DefStudio\WiredTables\Exceptions\ActionException;
use DefStudio\WiredTables\WiredTable;

/**
 * @mixin WiredTable
 */
trait HasActions
{
    /**
     * @var Action[]
     */
    private array $_actions = [];

    private bool $_actionsLocked = true;

    public function bootHasActions(): void
    {
        $this->_actionsLocked = false;
        $this->actions();
        $this->_actionsLocked = true;
    }

    protected function actions(): void
    {
        // no actions by default
    }

    /**
     * @return Action[]
     */
    public function getActionsProperty(): array
    {
        return $this->_actions;
    }

    public function action(string $name, string $method = null): Action
    {
        if ($this->_actionsLocked) {
            throw ActionException::locked();
        }

        if ($this->getAction($name) !== null) {
            throw ActionException::duplicated($name);
        }

        $this->_actions[] = $action = new Action($this, $name, $method);

        return $action;
    }

    public function shouldShowActionsSelector(): bool
    {
        return collect($this->_actions)->some(fn (Action $action) => $action->isVisible());
    }

    public function getAction(string $name): Action|null
    {
        return collect($this->_actions)->first(fn (Action $action) => $action->name() === $name);
    }

    public function hasActions(): bool
    {
        return count($this->_actions) > 0;
    }

    public function handleAction($actionName, ...$args): void
    {
        $action = $this->getAction($actionName);

        if (empty($action)) {
            throw ActionException::notFound($actionName);
        }

        $action->processHandler(...$args);
    }
}

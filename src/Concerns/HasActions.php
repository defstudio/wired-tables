<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\WiredTables\Concerns;

use DefStudio\WiredTables\Elements\Action;
use DefStudio\WiredTables\Enums\Config;
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
    private array $_actions;

    private bool $_actionsLocked = true;

    /** @var array<int, int|string> */
    public array $selection;

    public function bootHasActions(): void
    {
        $this->_actions = [];

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
            throw ActionException::duplicatedAction($name);
        }

        $this->_actions[] = $action = new Action($this, $name, $method);

        return $action;
    }

    public function getAction(string $name): Action|null
    {
        return collect($this->_actions)->first(fn (Action $action) => $action->name() === $name);
    }

    public function hasActions(): bool
    {
        return count($this->_actions) > 0;
    }

    public function shouldShowRowsSelector(): bool
    {
        return collect($this->_actions)->some(fn (Action $action) => $action->requiresRowsSelection());
    }

    public function shouldShowActionsSelector(): bool
    {
        if (!$this->hasActions()) {
            return false;
        }

        if ($this->config(Config::always_show_actions)) {
            return true;
        }

        if (collect($this->_actions)->some(fn (Action $action) => !$action->requiresRowsSelection())) {
            return true;
        }

        if (!empty($this->selection)) {
            return true;
        }

        return false;
    }
}

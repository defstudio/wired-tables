<?php

use DefStudio\WiredTables\Configurations\Configuration;
use DefStudio\WiredTables\Enums\Config;

it('can set a parent configuration', function () {
    $parent = new class () extends Configuration {
        public function __construct()
        {
            $this->set(Config::name, 'foo');
        }
    };

    $child = new class () extends Configuration {
    };

    $child->setParentConfiguration($parent);

    expect($child->get(Config::name))->toBe('foo');
});

it('can set and get a config value', function () {
    $config = new class () extends Configuration {
    };

    $config->set(Config::name, 'foo');

    expect($config->get(Config::name))
        ->toBe('foo');
});

it("can return it's config to array", function () {
    $config = new class () extends Configuration {
        public function __construct()
        {
            $this->set(Config::name, 'foo');
            $this->set(Config::db_column, 'bar');
        }
    };

    expect($config->toArray())->toBe([
        'name' => 'foo',
        'db_column' => 'bar',
    ]);
});

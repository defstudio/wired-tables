<?php

use DefStudio\WiredTables\WiredTable;

test('main view is defined', function(){
    expect(app(WiredTable::class)->mainView())
        ->toBe("wired-tables::main");
});

test('main view can be overriden', function () {
    $table = new class extends WiredTable{
        public function mainView(): string
        {
            return 'custom-view';
        }
    };


});

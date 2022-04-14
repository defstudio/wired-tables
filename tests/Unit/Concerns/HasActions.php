<?php

test('actions are booted', function(){
   $table = fakeTable();

   expect($table->actions)
       ->toBeArray()
       ->not->toBeEmpty();
});

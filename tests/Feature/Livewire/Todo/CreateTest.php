<?php

declare(strict_types=1);

use App\Livewire\Todos\Create;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(Create::class)
        ->assertStatus(200);
});

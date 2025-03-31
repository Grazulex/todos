<?php

declare(strict_types=1);

use App\Livewire\Todo\Index;
use Livewire\Livewire;

it('renders successfully', function (): void {
    $user = App\Models\User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200);
});

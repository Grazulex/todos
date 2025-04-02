<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ __('todos.title') }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ __('todis.title_description') }}
        </x-slot:subtitle>
        <x-slot:buttons>
            <flux:modal.trigger name="create-todo">
                <flux:button variant="primary" icon="plus">
                    {{ __('todos.create') }}
                </flux:button>
            </flux:modal.trigger>
            <livewire:todos.create />
        </x-slot:buttons>
    </x-page-heading>

    <livewire:todos.edit />

    <flux:modal name="delete-todo" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Todo?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this Todo.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="destroy">Delete Todo</flux:button>
            </div>
        </div>
    </flux:modal>


    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <flux:input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto"/>
        <flux:spacer/>

        <flux:select wire:model.live="perPage" class="!w-auto">
            <flux:select.option value="10">{{ __('global.10_per_page') }}</flux:select.option>
            <flux:select.option value="25">{{ __('global.25_per_page') }}</flux:select.option>
            <flux:select.option value="50">{{ __('global.50_per_page') }}</flux:select.option>
            <flux:select.option value="100">{{ __('global.100_per_page') }}</flux:select.option>
        </flux:select>
    </div>

    <flux:table :paginate="$todos">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection" wire:click="sort('id')">ID</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Date</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'type'" :direction="$sortDirection" wire:click="sort('type')">Type</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection" wire:click="sort('title')">Title</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($todos as $todo)
                <flux:table.row wire:key="{{ $todo->id }}">
                    <flux:table.cell>{{ $todo->id }}</flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $todo->created_at->diffForHumans() }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $todo->type->color()  }}" class="text-xs">
                            {{ $todo->type->label() }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell variant="strong">
                        {{ $todo->title }}
                        @if ($todo->description)
                            <flux:tooltip toggleable>
                                <flux:button icon="information-circle" size="sm" variant="ghost" />
                                <flux:tooltip.content class="max-w-[20rem] space-y-2 text-wrap">
                                    {{ $todo->description }}
                                </flux:tooltip.content>
                            </flux:tooltip>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button.group>
                            <flux:button size="sm" icon="pencil" wire:click="edit({{ $todo->id }})" />
                            <flux:button size="sm" variant="danger" icon="trash" wire:click="delete({{ $todo->id }})" />
                        </flux:button.group>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>


</section>

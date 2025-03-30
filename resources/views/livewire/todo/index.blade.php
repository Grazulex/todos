<section class="w-full">
    <x-page-heading>
        <x-slot:title>
            {{ __('todos.title') }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ __('todis.title_description') }}
        </x-slot:subtitle>
        <x-slot:buttons>
            <flux:button href="{{ route('todos.create') }}" variant="primary" icon="plus">
                {{ __('todos.create_user') }}
            </flux:button>
        </x-slot:buttons>
    </x-page-heading>

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
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Title</flux:table.column>
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
                        <flux:tooltip toggleable>
                            <flux:button icon="information-circle" size="sm" variant="ghost" />
                            <flux:tooltip.content class="max-w-[20rem] space-y-2 text-wrap">
                                {{ $todo->description }}
                            </flux:tooltip.content>
                        </flux:tooltip>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>


</section>

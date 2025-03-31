<div>
    <flux:modal name="create-todo" class="md:w-200">
        <div class="space-y-6">
            <div>
                <flux:heading size="xl">
                    {{ __('todos.create') }}
                </flux:heading>
                <flux:subheading size="lg">
                    {{ __('todos.create_description') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="title" label="{{ __('todos.title') }}" placeholder="{{ __('todos.title_placeholder') }}" />

            <flux:textarea wire:model="description" label="{{ __('todos.description') }}" placeholder="{{ __('todos.description_placeholder') }}" />

            <flux:select wire:model="type" label="{{ __('todos.type') }}">
                @foreach($types as $type)
                    <flux:select.option value="{{ $type->value }}">{{ $type->label() }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="create">
                    {{ __('todos.create') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>

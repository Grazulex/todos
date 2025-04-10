<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-r dark:border-neutral-800">
                <div class="absolute inset-0 bg-neutral-900"></div>
                <flux:brand href="{{ route('dashboard') }}" name="{{ config('app.name') }}" class="flex flex-col items-center gap-2 font-medium"  wire:navigate>
                    <x-slot name="logo" class="bg-accent text-accent-foreground">
                        <i class="font-serif font-bold">T</i>
                    </x-slot>
                </flux:brand>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <flux:heading size="lg">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading>{{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <flux:brand href="{{ route('dashboard') }}" name="{{ config('app.name') }}" class="flex flex-col items-center gap-2 font-medium"  wire:navigate>
                        <x-slot name="logo" class="bg-accent text-accent-foreground">
                            <i class="font-serif font-bold">T</i>
                        </x-slot>
                    </flux:brand>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>

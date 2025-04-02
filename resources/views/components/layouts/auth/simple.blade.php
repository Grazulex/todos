<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <flux:brand href="{{ route('dashboard') }}" name="{{ config('app.name') }}" class="flex flex-col items-center gap-2 font-medium"  wire:navigate>
                    <x-slot name="logo" class="bg-accent text-accent-foreground">
                        <i class="font-serif font-bold">T</i>
                    </x-slot>
                </flux:brand>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>

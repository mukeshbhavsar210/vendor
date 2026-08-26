<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-filament::section>
            <h2 class="text-xl font-bold">Reviews</h2>

            <p class="text-3xl mt-2">
                {{ \App\Models\Review::count() }}
            </p>
        </x-filament::section>
    </div>
</x-filament-panels::page>
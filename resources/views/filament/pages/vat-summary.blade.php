<x-filament::page>
    <form wire:submit.prevent="runReport" class="space-y-4">
        {{ $this->form }}
        <x-filament::button type="submit">Run report</x-filament::button>
    </form>

    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-lg bg-white p-4 shadow">
            <p class="text-sm text-gray-500">Output VAT</p>
            <p class="text-2xl font-semibold">£{{ number_format($figures['output'] ?? 0, 2) }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow">
            <p class="text-sm text-gray-500">Input VAT</p>
            <p class="text-2xl font-semibold">£{{ number_format($figures['input'] ?? 0, 2) }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow">
            <p class="text-sm text-gray-500">VAT payable</p>
            <p class="text-2xl font-semibold">£{{ number_format($figures['payable'] ?? 0, 2) }}</p>
        </div>
    </div>
</x-filament::page>

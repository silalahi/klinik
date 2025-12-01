<div class="space-y-3 sm:space-y-0 dark:border-zinc-700 sm:flex sm:items-center sm:justify-between">
    <div class="w-full flex items-center gap-5">
        <flux:button size="sm" variant="ghost" href="{{ route('patients.index') }}" icon="arrow-left" wire:navigate>
            {{ __('Back') }}
        </flux:button>
        <div class="flex space-x-2 items-center">
            <div>
                <flux:avatar size="xl" :color="$patient->status->color()" :name="$patient->name" />
            </div>
            <div>
                <flux:heading size="xl" level="1" class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $patient->name }}</flux:heading>
                <flux:subheading>{{ __('Medical Record No') }}: <span class="text-indigo-500 font-medium">{{ $patient->medical_record_number }}</span></flux:subheading>
            </div>
        </div>
    </div>
</div>

<div class="space-y-6">
    <div class="border-b border-gray-200 pb-5 sm:flex sm:items-center sm:justify-between">
        <flux:heading size="xl" level="1" class="font-semibold">{{ __('Patients') }}</flux:heading>
        <div class="flex space-x-3">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="{{ __('Search by name, MR number, or phone...') }}" clearable class="w-96!" class:input="rounded-full!" />
            <flux:button variant="primary" href="#" icon="plus" class="rounded-full!">{{ __('Add Patient') }}</flux:button>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        {{ __('MR Number') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        {{ __('Name') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        {{ __('Date of Birth') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        {{ __('Phone') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">{{ __('Actions') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-800">
                @forelse ($patients as $patient)
                    <tr wire:key="patient-{{ $patient->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-900/50">
                        <td class="whitespace-nowrap px-6 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $patient->medical_record_number }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $patient->name }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $patient->date_of_birth?->format('d M Y') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $patient->phone ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-3 text-sm">
                            <flux:badge variant="pill" :color="$patient->status->color()" size="sm">
                                {{ $patient->status->label() }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-3 text-right text-sm font-medium">
                            <flux:button variant="ghost" size="sm" href="#" icon="pencil">{{ __('Edit') }}</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            @if ($search)
                                {{ __('No patients found matching your search.') }}
                            @else
                                {{ __('No patients yet.') }}
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $patients->links() }}
    </div>
</div>

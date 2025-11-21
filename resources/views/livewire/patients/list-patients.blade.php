<div class="space-y-5">
    <div class="space-y-3 sm:space-y-0 sm:border-b sm:border-zinc-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1" class="font-semibold">{{ __('Patients') }}</flux:heading>
            <flux:subheading size="lg">{{ __('Manage patients data') }}</flux:subheading>
        </div>

        <div class="flex space-x-3">
            <flux:modal.trigger name="create-patient-modal">
                <flux:button variant="primary" icon="plus" class="!rounded-full">
                    {{ __('Add Patient') }}
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <div class="flex w-full space-x-3">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="{{ __('Search by name, medical record, or phone') }}" clearable="true" class:input="!rounded-full" />
    </div>

    <div class="overflow-hidden ring-1 ring-zinc-200 dark:border-zinc-700 sm:rounded-lg">
        <table class="relative min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-900">
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-300 sm:pl-6">
                    {{ __('Medical Record Number') }}
                </th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-300 lg:table-cell">
                    {{ __('Name') }}
                </th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-300 lg:table-cell">
                    {{ __('Date of Birth') }}
                </th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-300 lg:table-cell">
                    {{ __('Phone') }}
                </th>
                <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-300">
                    {{ __('Status') }}
                </th>
                <th scope="col" class="py-3.5 pl-3 pr-4 sm:pr-6">
                    <span class="sr-only">Select</span>
                </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-800">
            @forelse($patients as $patient)
                <tr wire:key="patient-{{ $patient->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-900/50">
                    <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-indigo-500 hover:underline transition-all">
                            <a href="#">{{ $patient->medical_record_number }}</a>
                        </div>
                        <div class="mt-1 flex flex-col text-zinc-700 sm:block lg:hidden">
                            <span>{{ $patient->name }}</span>
                            <span class="hidden sm:inline">·</span>
                            <span>{{ $patient->date_of_birth?->format('d F Y') }}</span>
                            <span>{{ $patient->phone }}</span>
                        </div>
                    </td>
                    <td class="hidden px-3 py-3.5 text-sm text-zinc-700 lg:table-cell">
                        {{ $patient->name }}
                    </td>
                    <td class="hidden px-3 py-3.5 text-sm text-zinc-700 lg:table-cell">
                        {{ $patient->date_of_birth?->format('d F Y') }}
                    </td>
                    <td class="hidden px-3 py-3.5 text-sm text-zinc-700 lg:table-cell">
                        {{ $patient->phone }}
                    </td>
                    <td class="px-3 py-3.5 text-sm text-zinc-700">
                        <span data-slot="badge"
                              class="inline-flex items-center justify-center rounded-full border border-zinc-200 py-0.5 w-fit whitespace-nowrap shrink-0 [&>svg]:size-4 gap-1 [&>svg]:pointer-events-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden [a&]:hover:bg-accent [a&]:hover:text-accent-foreground text-muted-foreground px-1.5 pr-2.5">
                            @if($patient->status == App\Enums\PatientStatus::Active)
                                <flux:icon.check-circle variant="solid" class="text-green-500 dark:text-green-400" />
                            @elseif($patient->status == App\Enums\PatientStatus::Inactive)
                                <flux:icon.minus-circle variant="solid" class="text-yellow-500 dark:text-yellow-400" />
                            @else
                                <flux:icon.x-circle variant="solid" class="text-zinc-500 dark:text-zinc-400" />
                            @endif
                            {{ $patient->status->label() }}
                        </span>
                    </td>
                    <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <flux:button size="sm">Edit</flux:button>
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


    <livewire:patients.create-patient-modal/>
</div>

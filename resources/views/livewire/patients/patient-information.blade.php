<div class="space-y-5">
    @include('livewire.patients._header')

    @include('livewire.patients._tabs')

    <div>
        <span data-slot="badge" class="inline-flex items-center justify-center rounded-full border border-zinc-200 dark:border-zinc-700 py-0.5 w-fit whitespace-nowrap shrink-0 [&>svg]:size-4 gap-1 [&>svg]:pointer-events-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden [a&]:hover:bg-accent [a&]:hover:text-accent-foreground text-muted-foreground px-1.5 pr-2.5">
            @if($patient->status == App\Enums\PatientStatus::Active)
                <flux:icon.check-circle variant="solid" class="text-green-500 dark:text-green-400" />
            @elseif($patient->status == App\Enums\PatientStatus::Inactive)
                <flux:icon.minus-circle variant="solid" class="text-yellow-500 dark:text-yellow-400" />
            @else
                <flux:icon.x-circle variant="solid" class="text-zinc-500 dark:text-zinc-400" />
            @endif
            {{ $patient->status->label() }}
        </span>
    </div>

    <livewire:patients.patient-basic-information :patient="$patient" />

    <livewire:patients.patient-contact-information :patient="$patient" />

    <livewire:patients.patient-emergency-contact :patient="$patient" />

    {{-- Medical Information --}}
    <div class="overflow-hidden ring-1 ring-zinc-200 dark:ring-zinc-700 sm:rounded-lg bg-white dark:bg-zinc-800">
        <div class="px-4 py-5 sm:px-6 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">{{ __('Medical Information') }}</flux:heading>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Medical history and notes') }}</p>
            </div>
            <flux:button size="sm" variant="ghost" icon="pencil" wire:click="$dispatch('edit-medical-info')">
                {{ __('Edit') }}
            </flux:button>
        </div>
        <div class="border-t border-zinc-100 dark:border-zinc-700">
            <dl class="divide-y divide-zinc-100 dark:divide-zinc-700">
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Allergies') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0 whitespace-pre-line">{{ $patient->allergies ?? '-' }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Medical History') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0 whitespace-pre-line">{{ $patient->medical_history ?? '-' }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Additional Notes') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0 whitespace-pre-line">{{ $patient->notes ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Include Edit Components --}}
{{--    <livewire:patients.edit-patient-personal-information :patient="$patient" :key="'edit-personal-'.$patient->id" />--}}
{{--    <livewire:patients.edit-patient-contact-information :patient="$patient" :key="'edit-contact-'.$patient->id" />--}}
{{--    <livewire:patients.edit-patient-emergency-contact :patient="$patient" :key="'edit-emergency-'.$patient->id" />--}}
{{--    <livewire:patients.edit-patient-medical-information :patient="$patient" :key="'edit-medical-'.$patient->id" />--}}
</div>

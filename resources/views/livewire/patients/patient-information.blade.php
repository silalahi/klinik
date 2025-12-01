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

    <livewire:patients.patient-medical-information :patient="$patient" />
</div>

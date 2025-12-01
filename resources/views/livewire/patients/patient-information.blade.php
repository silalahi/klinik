<div class="space-y-5">
    @include('livewire.patients._header')

    @include('livewire.patients._tabs')

    <livewire:patients.patient-status-management :patient="$patient" />

    <livewire:patients.patient-basic-information :patient="$patient" />

    <livewire:patients.patient-contact-information :patient="$patient" />

    <livewire:patients.patient-emergency-contact :patient="$patient" />

    <livewire:patients.patient-medical-information :patient="$patient" />
</div>

<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PatientEmergencyContact extends Component
{
    public Patient $patient;

    #[Validate('nullable|string|max:255')]
    public $emergency_contact_name = '';

    #[Validate('nullable|string|max:20')]
    public $emergency_contact_phone = '';

    #[Validate('nullable|string|max:100')]
    public $emergency_contact_relationship = '';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;

        $this->emergency_contact_name = $patient->emergency_contact_name ?? '';
        $this->emergency_contact_phone = $patient->emergency_contact_phone ?? '';
        $this->emergency_contact_relationship = $patient->emergency_contact_relationship ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->patient->update([
            'emergency_contact_name' => $validated['emergency_contact_name'] ?: null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?: null,
            'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?: null,
        ]);

        Flux::modal('edit-patient-emergency-contact')->close();

        $this->dispatch('patient-updated');

        Flux::toast(__('Emergency contact updated successfully.'), variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.patients.patient-emergency-contact');
    }
}

<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PatientMedicalInformation extends Component
{
    public Patient $patient;

    #[Validate('nullable|string')]
    public $allergies = '';

    #[Validate('nullable|string')]
    public $medical_history = '';

    #[Validate('nullable|string')]
    public $notes = '';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;

        $this->allergies = $this->patient->allergies ?? '';
        $this->medical_history = $this->patient->medical_history ?? '';
        $this->notes = $this->patient->notes ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->patient->update([
            'allergies' => $validated['allergies'] ?: null,
            'medical_history' => $validated['medical_history'] ?: null,
            'notes' => $validated['notes'] ?: null,
        ]);

        Flux::modal('edit-patient-medical-information')->close();

        $this->dispatch('patient-updated');

        Flux::toast(__('Patient information updated successfully.'), variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.patients.patient-medical-information');
    }
}

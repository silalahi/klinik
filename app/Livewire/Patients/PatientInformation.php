<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Livewire\Attributes\On;
use Livewire\Component;

class PatientInformation extends Component
{
    public Patient $patient;

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
    }

    #[On('patient-updated')]
    public function refreshPatient(): void
    {
        $this->patient->refresh();
    }

    public function render()
    {
        return view('livewire.patients.patient-information');
    }
}

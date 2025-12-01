<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PatientContactInformation extends Component
{
    public Patient $patient;

    #[Validate('required|string|max:20')]
    public $phone = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('required|string')]
    public $address = '';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;

        $this->phone = $patient->phone ?? '';
        $this->email = $patient->email ?? '';
        $this->address = $patient->address ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->patient->update([
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?: null,
            'address' => $validated['address'],
        ]);

        Flux::modal('edit-patient-contact-information')->close();

        $this->dispatch('patient-updated');

        Flux::toast(__('Patient information updated successfully.'), variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.patients.patient-contact-information');
    }
}

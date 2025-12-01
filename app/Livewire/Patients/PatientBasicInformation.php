<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PatientBasicInformation extends Component
{
    public Patient $patient;

    #[Validate('required|string|max:50')]
    public $name = '';

    #[Validate('nullable|string|min:5|max:50')]
    public $medical_record_number = '';

    #[Validate('required|string|max:255')]
    public $id_number = '';

    #[Validate('nullable|string|max:255')]
    public $place_of_birth = '';

    #[Validate('required|string|max:255')]
    public $date_of_birth = '';

    #[Validate('required|in:male,female,other')]
    public $gender = '';

    #[Validate('nullable|string')]
    public $marital_status = '';

    #[Validate('nullable|string')]
    public $religion = '';

    #[Validate('nullable|string|max:10')]
    public $blood_type = '';

    #[Validate('nullable|string|max:255')]
    public $occupation = '';

    public function mount(Patient $patient)
    {
        $this->patient = $patient;

        $this->name = $patient->name;
        $this->medical_record_number = $patient->medical_record_number ?? '';
        $this->id_number = $patient->id_number;
        $this->place_of_birth = $patient->place_of_birth ?? '';
        $this->date_of_birth = $patient->date_of_birth?->format('Y-m-d') ?? '';
        $this->gender = $patient->gender?->value ?? '';
        $this->marital_status = $patient->marital_status?->value ?? '';
        $this->religion = $patient->religion?->value ?? '';
        $this->blood_type = $patient->blood_type ?? '';
        $this->occupation = $patient->occupation ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->patient->update([
            'name' => $validated['name'],
            'medical_record_number' => $validated['medical_record_number'] ?: null,
            'id_number' => $validated['id_number'],
            'place_of_birth' => $validated['place_of_birth'] ?: null,
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'marital_status' => $validated['marital_status'] ?: null,
            'religion' => $validated['religion'] ?: null,
            'blood_type' => $validated['blood_type'] ?: null,
            'occupation' => $validated['occupation'] ?: null,
        ]);

        Flux::modal('edit-patient-basic-information')->close();

        $this->dispatch('patient-updated');

        Flux::toast(__('Patient information updated successfully.'), variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.patients.patient-basic-information');
    }
}

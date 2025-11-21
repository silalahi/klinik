<?php

namespace App\Livewire\Patients;

use App\Enums\Gender;
use App\Enums\PatientStatus;
use App\Models\Patient;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePatient extends Component
{
    #[Validate('nullable|string|min:5|max:50')]
    public string $medical_record_number;

    #[Validate('required|string|max:255')]
    public string $id_number = '';

    #[Validate('required|string|max:50')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $date_of_birth = '';

    #[Validate('required|in:male,female,other')]
    public string $gender = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('required|string')]
    public string $address = '';

    public function save()
    {
        $this->validate();

        $patient = Patient::create([
            'medical_record_number' => $this->medical_record_number ?? 'MR-'.now()->format('ymd-his'),
            'id_number' => $this->id_number,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => PatientStatus::Active,
        ]);

        return $this->redirect(route('patients.index', ['patient' => $patient]), navigate: true);
    }

    public function render()
    {
        return view('livewire.patients.create-patient', [
            'genders' => Gender::cases(),
        ]);
    }
}

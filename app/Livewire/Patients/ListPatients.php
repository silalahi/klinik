<?php

namespace App\Livewire\Patients;

use App\Livewire\WithSearch;
use App\Models\Patient;
use Carbon\Carbon;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ListPatients extends Component
{
    use WithPagination, WithSearch;

    //    #[On('patient-created')]
//    public function refreshPatients(): void
//    {
//        $this->resetPage();
//    }
//
//    public function deletePatient(int $patientId): void
//    {
//        $patient = Patient::findOrFail($patientId);
//        $patient->delete();
//
//        Flux::modal('delete-patient-'.$patientId)->close();
//
//        session()->flash('success', __('Patient deleted successfully.'));
//
//        $this->resetPage();
//    }

    public function render(): View
    {
        $patients = Patient::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('medical_record_number', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.patients.list-patients', [
            'patients' => $patients,
        ]);
    }
}

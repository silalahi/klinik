<?php

namespace App\Livewire\Patients;

use App\Livewire\WithSearch;
use App\Models\Patient;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ListPatients extends Component
{
    use WithPagination, WithSearch;

    public function render(): View
    {
        $patients = Patient::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('medical_record_number', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.patients.list-patients', [
            'patients' => $patients,
        ]);
    }
}

<?php

namespace App\Livewire\Patients;

use App\Livewire\WithSearch;
use App\Models\Patient;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ListPatients extends Component
{
    use WithPagination, WithSearch;

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    #[Computed]
    public function patients(): LengthAwarePaginator
    {
        return Patient::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('medical_record_number', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(10);
    }

    public function render(): View
    {
        return view('livewire.patients.list-patients');
    }
}

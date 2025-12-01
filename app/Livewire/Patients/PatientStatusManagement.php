<?php

namespace App\Livewire\Patients;

use App\Enums\PatientStatus;
use App\Models\Patient;
use App\Rules\ValidStatusTransition;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PatientStatusManagement extends Component
{
    public Patient $patient;

    #[Validate('required|string')]
    public $new_status = '';

    #[Validate('nullable|string|max:1000')]
    public $reason = '';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
        $this->new_status = $patient->status->value;
    }

    #[On('patient-updated')]
    public function refreshPatient(): void
    {
        $this->patient->refresh();
        $this->new_status = $this->patient->status->value;
    }

    #[Computed]
    public function availableStatuses(): array
    {
        // If patient is deceased, return empty array (cannot change)
        if ($this->patient->status === PatientStatus::Deceased) {
            return [];
        }

        return array_map(
            fn ($status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            PatientStatus::cases()
        );
    }

    #[Computed]
    public function statusHistory(): Collection
    {
        return $this->patient->statusActivities()
            ->with('causer')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                $oldStatus = $activity->properties['old']['status'] ?? null;
                $newStatus = $activity->properties['attributes']['status'] ?? null;
                $reason = $activity->properties['attributes']['status_change_reason'] ?? null;

                return [
                    'id' => $activity->id,
                    'user' => $activity->causer?->name ?? __('System'),
                    'old_status' => $oldStatus ? PatientStatus::from($oldStatus)->label() : null,
                    'new_status' => $newStatus ? PatientStatus::from($newStatus)->label() : null,
                    'reason' => $reason,
                    'created_at' => $activity->created_at,
                ];
            });
    }

    public function save(): void
    {
        // Add custom validation rule
        $this->validate([
            'new_status' => ['required', 'string', new ValidStatusTransition($this->patient)],
            'reason' => 'nullable|string|max:1000',
        ]);

        // Check if status actually changed
        if ($this->new_status === $this->patient->status->value) {
            Flux::modal('edit-patient-status')->close();
            Flux::toast(__('Status unchanged.'), variant: 'warning');

            return;
        }

        // Get old status before update
        $oldStatus = $this->patient->status->value;

        // Update patient status
        $this->patient->update([
            'status' => $this->new_status,
        ]);

        // Manually log the activity with reason
        activity()
            ->performedOn($this->patient)
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties([
                'old' => ['status' => $oldStatus],
                'attributes' => [
                    'status' => $this->new_status,
                    'status_change_reason' => $this->reason ?: null,
                ],
            ])
            ->log('updated');

        // Reset form
        $this->reason = '';

        // Close modal and notify
        Flux::modal('edit-patient-status')->close();

        $this->dispatch('patient-updated');

        Flux::toast(__('Patient status updated successfully.'), variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.patients.patient-status-management');
    }
}

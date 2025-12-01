<?php

use App\Enums\PatientStatus;
use App\Livewire\Patients\PatientStatusManagement;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

test('component loads with current patient status', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->assertSet('new_status', 'active')
        ->assertStatus(200);
});

test('authenticated user can change status from Active to Inactive', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'inactive')
        ->set('reason', 'Patient requested to be marked inactive')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('patient-updated');

    $patient->refresh();

    expect($patient->status)->toBe(PatientStatus::Inactive);
});

test('authenticated user can change status from Inactive to Active', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Inactive]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'active')
        ->set('reason', 'Patient reactivated')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('patient-updated');

    $patient->refresh();

    expect($patient->status)->toBe(PatientStatus::Active);
});

test('authenticated user can change status from Active to Deceased', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'deceased')
        ->set('reason', 'Patient passed away')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('patient-updated');

    $patient->refresh();

    expect($patient->status)->toBe(PatientStatus::Deceased);
});

test('authenticated user can change status from Inactive to Deceased', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Inactive]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'deceased')
        ->set('reason', 'Patient passed away')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('patient-updated');

    $patient->refresh();

    expect($patient->status)->toBe(PatientStatus::Deceased);
});

test('cannot change status from Deceased to Active', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Deceased]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'active')
        ->call('save')
        ->assertHasErrors(['new_status']);
});

test('cannot change status from Deceased to Inactive', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Deceased]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'inactive')
        ->call('save')
        ->assertHasErrors(['new_status']);
});

test('warning shown when selecting same status', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'active')
        ->call('save')
        ->assertHasNoErrors();

    // Status should remain unchanged
    $patient->refresh();
    expect($patient->status)->toBe(PatientStatus::Active);
});

test('reason field is optional', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'inactive')
        ->set('reason', '')
        ->call('save')
        ->assertHasNoErrors();

    $patient->refresh();
    expect($patient->status)->toBe(PatientStatus::Inactive);
});

test('reason field accepts valid text', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'inactive')
        ->set('reason', 'This is a valid reason for the status change')
        ->call('save')
        ->assertHasNoErrors();
});

test('reason field cannot exceed 1000 characters', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'inactive')
        ->set('reason', str_repeat('a', 1001))
        ->call('save')
        ->assertHasErrors(['reason' => 'max']);
});

test('status change creates activity log entry with reason', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'inactive')
        ->set('reason', 'Test reason for status change')
        ->call('save');

    $activity = Activity::where('subject_id', $patient->id)
        ->where('subject_type', Patient::class)
        ->whereNotNull('properties->attributes->status')
        ->latest()
        ->first();

    $properties = $activity->properties->toArray();

    expect($activity)->not->toBeNull()
        ->and($properties['old']['status'])->toBe('active')
        ->and($properties['attributes']['status'])->toBe('inactive')
        ->and($properties['attributes']['status_change_reason'])->toBe('Test reason for status change')
        ->and($activity->causer_id)->toBe($user->id);
});

test('status history displays correctly with changes', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    // Make an actual status change to create activity log
    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'inactive')
        ->set('reason', 'Test reason for status history')
        ->call('save');

    // Refresh patient
    $patient->refresh();

    // Now test the component's status history
    $component = Livewire::test(PatientStatusManagement::class, ['patient' => $patient]);

    $statusHistory = $component->get('statusHistory');

    expect($statusHistory)->toHaveCount(1)
        ->and($statusHistory->first()['old_status'])->toBe('Active')
        ->and($statusHistory->first()['new_status'])->toBe('Inactive')
        ->and($statusHistory->first()['reason'])->toBe('Test reason for status history')
        ->and($statusHistory->first()['user'])->toBe($user->name);
});

test('patient-updated event dispatched after save', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    Livewire::test(PatientStatusManagement::class, ['patient' => $patient])
        ->set('new_status', 'inactive')
        ->call('save')
        ->assertDispatched('patient-updated');
});

test('available statuses is empty for deceased patients', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Deceased]);

    $this->actingAs($user);

    $component = Livewire::test(PatientStatusManagement::class, ['patient' => $patient]);

    $availableStatuses = $component->get('availableStatuses');

    expect($availableStatuses)->toBeArray()
        ->and($availableStatuses)->toBeEmpty();
});

test('available statuses includes all statuses for non-deceased patients', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    $component = Livewire::test(PatientStatusManagement::class, ['patient' => $patient]);

    $availableStatuses = $component->get('availableStatuses');

    expect($availableStatuses)->toHaveCount(3)
        ->and(collect($availableStatuses)->pluck('value')->toArray())->toContain('active', 'inactive', 'deceased');
});

test('component refreshes when patient-updated event is dispatched', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['status' => PatientStatus::Active]);

    $this->actingAs($user);

    $component = Livewire::test(PatientStatusManagement::class, ['patient' => $patient]);

    // Update patient in database
    $patient->update(['status' => PatientStatus::Inactive]);

    // Dispatch event
    $component->dispatch('patient-updated');

    // Component should have refreshed
    expect($component->get('patient')->status)->toBe(PatientStatus::Inactive)
        ->and($component->get('new_status'))->toBe('inactive');
});

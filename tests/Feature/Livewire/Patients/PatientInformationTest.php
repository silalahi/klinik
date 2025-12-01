<?php

use App\Livewire\Patients\PatientInformation;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

test('component renders successfully', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientInformation::class, ['patient' => $patient])
        ->assertStatus(200);
});

test('component displays patient information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'name' => 'John Doe',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientInformation::class, ['patient' => $patient])
        ->assertSee('John Doe');
});

test('component refreshes patient when patient-updated event is dispatched', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'name' => 'Original Name',
    ]);

    $this->actingAs($user);

    $component = Livewire::test(PatientInformation::class, ['patient' => $patient]);

    // Update the patient in the database
    $patient->update(['name' => 'Updated Name']);

    // Dispatch the patient-updated event
    $component->dispatch('patient-updated');

    // The component should have refreshed the patient
    expect($component->get('patient')->name)->toBe('Updated Name');
});

test('component mounts with correct patient data', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(PatientInformation::class, ['patient' => $patient]);

    expect($component->get('patient')->id)->toBe($patient->id);
});

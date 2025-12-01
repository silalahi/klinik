<?php

use App\Livewire\Patients\PatientMedicalInformation;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

test('component loads patient medical information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'allergies' => 'Penicillin',
        'medical_history' => 'Hypertension',
        'notes' => 'Patient requires regular check-ups',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientMedicalInformation::class, ['patient' => $patient])
        ->assertSet('allergies', 'Penicillin')
        ->assertSet('medical_history', 'Hypertension')
        ->assertSet('notes', 'Patient requires regular check-ups');
});

test('component handles null medical information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'allergies' => null,
        'medical_history' => null,
        'notes' => null,
    ]);

    $this->actingAs($user);

    Livewire::test(PatientMedicalInformation::class, ['patient' => $patient])
        ->assertSet('allergies', '')
        ->assertSet('medical_history', '')
        ->assertSet('notes', '');
});

test('authenticated user can update patient medical information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'allergies' => 'None',
        'medical_history' => 'Healthy',
        'notes' => 'No issues',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientMedicalInformation::class, ['patient' => $patient])
        ->set('allergies', 'Latex allergy')
        ->set('medical_history', 'Type 2 Diabetes')
        ->set('notes', 'Requires insulin')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('patient-updated');

    $patient->refresh();

    expect($patient->allergies)->toBe('Latex allergy')
        ->and($patient->medical_history)->toBe('Type 2 Diabetes')
        ->and($patient->notes)->toBe('Requires insulin');
});

test('medical information fields can be empty', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'allergies' => 'Penicillin',
        'medical_history' => 'Hypertension',
        'notes' => 'Notes',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientMedicalInformation::class, ['patient' => $patient])
        ->set('allergies', '')
        ->set('medical_history', '')
        ->set('notes', '')
        ->call('save')
        ->assertHasNoErrors();

    $patient->refresh();

    expect($patient->allergies)->toBeNull()
        ->and($patient->medical_history)->toBeNull()
        ->and($patient->notes)->toBeNull();
});

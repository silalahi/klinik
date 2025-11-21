<?php

use App\Enums\Gender;
use App\Enums\PatientStatus;
use App\Livewire\Patients\CreatePatient;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

test('authenticated user can create a patient', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(CreatePatient::class)
        ->set('id_number', '123456789')
        ->set('name', 'John Doe')
        ->set('date_of_birth', '1990-01-01')
        ->set('gender', Gender::Male->value)
        ->set('phone', '081234567890')
        ->set('address', 'Jakarta Selatan')
        ->call('save')
        ->assertHasNoErrors();

    expect(Patient::where('name', 'John Doe')->exists())->toBeTrue();

    $patient = Patient::where('name', 'John Doe')->first();

    expect($patient->gender)->toBe(Gender::Male)
        ->and($patient->status)->toBe(PatientStatus::Active)
        ->and($patient->medical_record_number)->toStartWith('MR');
});

test('patient creation requires mandatory fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(CreatePatient::class)
        ->call('save')
        ->assertHasErrors(['name', 'date_of_birth', 'gender', 'phone', 'address']);
});

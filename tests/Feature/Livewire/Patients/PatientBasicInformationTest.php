<?php

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\Religion;
use App\Livewire\Patients\PatientBasicInformation;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

test('component loads patient basic information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'name' => 'John Doe',
        'id_number' => '1234567890',
        'place_of_birth' => 'Jakarta',
        'date_of_birth' => '1990-01-15',
        'gender' => Gender::Male,
        'blood_type' => 'O+',
        'occupation' => 'Engineer',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->assertSet('name', 'John Doe')
        ->assertSet('id_number', '1234567890')
        ->assertSet('place_of_birth', 'Jakarta')
        ->assertSet('date_of_birth', '1990-01-15')
        ->assertSet('gender', 'male')
        ->assertSet('blood_type', 'O+')
        ->assertSet('occupation', 'Engineer');
});

test('authenticated user can update patient basic information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'name' => 'Old Name',
        'id_number' => '1111111111',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('name', 'New Name')
        ->set('id_number', '2222222222')
        ->set('place_of_birth', 'Bandung')
        ->set('date_of_birth', '1995-06-20')
        ->set('gender', 'female')
        ->set('blood_type', 'A+')
        ->set('occupation', 'Doctor')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('patient-updated');

    $patient->refresh();

    expect($patient->name)->toBe('New Name')
        ->and($patient->id_number)->toBe('2222222222')
        ->and($patient->place_of_birth)->toBe('Bandung')
        ->and($patient->date_of_birth->format('Y-m-d'))->toBe('1995-06-20')
        ->and($patient->gender)->toBe(Gender::Female)
        ->and($patient->blood_type)->toBe('A+')
        ->and($patient->occupation)->toBe('Doctor');
});

test('name is required', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('id_number is required', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('id_number', '')
        ->call('save')
        ->assertHasErrors(['id_number' => 'required']);
});

test('date_of_birth is required', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('date_of_birth', '')
        ->call('save')
        ->assertHasErrors(['date_of_birth' => 'required']);
});

test('gender is required and must be valid', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('gender', '')
        ->call('save')
        ->assertHasErrors(['gender' => 'required']);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('gender', 'invalid')
        ->call('save')
        ->assertHasErrors(['gender' => 'in']);
});

test('name cannot exceed 50 characters', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('name', str_repeat('a', 51))
        ->call('save')
        ->assertHasErrors(['name' => 'max']);
});

test('medical_record_number must be at least 5 characters if provided', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('medical_record_number', 'MR1')
        ->call('save')
        ->assertHasErrors(['medical_record_number' => 'min']);
});

test('blood_type cannot exceed 10 characters', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('blood_type', str_repeat('a', 11))
        ->call('save')
        ->assertHasErrors(['blood_type' => 'max']);
});

test('optional fields can be empty', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->set('place_of_birth', '')
        ->set('marital_status', '')
        ->set('religion', '')
        ->set('blood_type', '')
        ->set('occupation', '')
        ->call('save')
        ->assertHasNoErrors(['place_of_birth', 'marital_status', 'religion', 'blood_type', 'occupation']);
});

test('component handles enums correctly', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'gender' => Gender::Male,
        'marital_status' => MaritalStatus::Married,
        'religion' => Religion::Islam,
    ]);

    $this->actingAs($user);

    Livewire::test(PatientBasicInformation::class, ['patient' => $patient])
        ->assertSet('gender', 'male')
        ->assertSet('marital_status', 'married')
        ->assertSet('religion', 'islam');
});

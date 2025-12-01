<?php

use App\Livewire\Patients\PatientEmergencyContact;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

test('component loads patient emergency contact information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'emergency_contact_name' => 'Jane Doe',
        'emergency_contact_phone' => '089876543210',
        'emergency_contact_relationship' => 'Spouse',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->assertSet('emergency_contact_name', 'Jane Doe')
        ->assertSet('emergency_contact_phone', '089876543210')
        ->assertSet('emergency_contact_relationship', 'Spouse');
});

test('component handles null emergency contact information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'emergency_contact_name' => null,
        'emergency_contact_phone' => null,
        'emergency_contact_relationship' => null,
    ]);

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->assertSet('emergency_contact_name', '')
        ->assertSet('emergency_contact_phone', '')
        ->assertSet('emergency_contact_relationship', '');
});

test('authenticated user can update patient emergency contact', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'emergency_contact_name' => 'Old Contact',
        'emergency_contact_phone' => '081111111111',
        'emergency_contact_relationship' => 'Parent',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->set('emergency_contact_name', 'New Contact')
        ->set('emergency_contact_phone', '082222222222')
        ->set('emergency_contact_relationship', 'Sibling')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('patient-updated');

    $patient->refresh();

    expect($patient->emergency_contact_name)->toBe('New Contact')
        ->and($patient->emergency_contact_phone)->toBe('082222222222')
        ->and($patient->emergency_contact_relationship)->toBe('Sibling');
});

test('all emergency contact fields are optional', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'emergency_contact_name' => 'Someone',
        'emergency_contact_phone' => '081234567890',
        'emergency_contact_relationship' => 'Friend',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->set('emergency_contact_name', '')
        ->set('emergency_contact_phone', '')
        ->set('emergency_contact_relationship', '')
        ->call('save')
        ->assertHasNoErrors();

    $patient->refresh();

    expect($patient->emergency_contact_name)->toBeNull()
        ->and($patient->emergency_contact_phone)->toBeNull()
        ->and($patient->emergency_contact_relationship)->toBeNull();
});

test('emergency contact name cannot exceed 255 characters', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->set('emergency_contact_name', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['emergency_contact_name' => 'max']);
});

test('emergency contact phone cannot exceed 20 characters', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->set('emergency_contact_phone', str_repeat('1', 21))
        ->call('save')
        ->assertHasErrors(['emergency_contact_phone' => 'max']);
});

test('emergency contact relationship cannot exceed 100 characters', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->set('emergency_contact_relationship', str_repeat('a', 101))
        ->call('save')
        ->assertHasErrors(['emergency_contact_relationship' => 'max']);
});

test('can save emergency contact with all fields filled', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->set('emergency_contact_name', 'Emergency Contact')
        ->set('emergency_contact_phone', '081234567890')
        ->set('emergency_contact_relationship', 'Mother')
        ->call('save')
        ->assertHasNoErrors();

    $patient->refresh();

    expect($patient->emergency_contact_name)->toBe('Emergency Contact')
        ->and($patient->emergency_contact_phone)->toBe('081234567890')
        ->and($patient->emergency_contact_relationship)->toBe('Mother');
});

test('can partially update emergency contact fields', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'emergency_contact_name' => null,
        'emergency_contact_phone' => null,
        'emergency_contact_relationship' => null,
    ]);

    $this->actingAs($user);

    Livewire::test(PatientEmergencyContact::class, ['patient' => $patient])
        ->set('emergency_contact_name', 'Contact Name')
        ->set('emergency_contact_phone', '')
        ->set('emergency_contact_relationship', '')
        ->call('save')
        ->assertHasNoErrors();

    $patient->refresh();

    expect($patient->emergency_contact_name)->toBe('Contact Name')
        ->and($patient->emergency_contact_phone)->toBeNull()
        ->and($patient->emergency_contact_relationship)->toBeNull();
});

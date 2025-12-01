<?php

use App\Livewire\Patients\PatientContactInformation;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

test('component loads patient contact information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'phone' => '081234567890',
        'email' => 'john@example.com',
        'address' => 'Jakarta Selatan',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->assertSet('phone', '081234567890')
        ->assertSet('email', 'john@example.com')
        ->assertSet('address', 'Jakarta Selatan');
});

test('component handles null contact information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'phone' => '081234567890',
        'email' => null,
        'address' => 'Jakarta',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->assertSet('phone', '081234567890')
        ->assertSet('email', '')
        ->assertSet('address', 'Jakarta');
});

test('authenticated user can update patient contact information', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'phone' => '081111111111',
        'email' => 'old@example.com',
        'address' => 'Old Address',
    ]);

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->set('phone', '082222222222')
        ->set('email', 'new@example.com')
        ->set('address', 'New Address')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('patient-updated');

    $patient->refresh();

    expect($patient->phone)->toBe('082222222222')
        ->and($patient->email)->toBe('new@example.com')
        ->and($patient->address)->toBe('New Address');
});

test('phone is required', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->set('phone', '')
        ->call('save')
        ->assertHasErrors(['phone' => 'required']);
});

test('address is required', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->set('address', '')
        ->call('save')
        ->assertHasErrors(['address' => 'required']);
});

test('email is optional', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->set('email', '')
        ->call('save')
        ->assertHasNoErrors(['email']);
});

test('email must be valid format', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->set('email', 'invalid-email')
        ->call('save')
        ->assertHasErrors(['email' => 'email']);
});

test('phone cannot exceed 20 characters', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->set('phone', str_repeat('1', 21))
        ->call('save')
        ->assertHasErrors(['phone' => 'max']);
});

test('email cannot exceed 255 characters', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    $longEmail = str_repeat('a', 246).'@test.com'; // 255 characters

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->set('email', $longEmail.'a') // 256 characters
        ->call('save')
        ->assertHasErrors(['email' => 'max']);
});

test('can update with valid email', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();

    $this->actingAs($user);

    Livewire::test(PatientContactInformation::class, ['patient' => $patient])
        ->set('email', 'valid@example.com')
        ->call('save')
        ->assertHasNoErrors();

    $patient->refresh();

    expect($patient->email)->toBe('valid@example.com');
});

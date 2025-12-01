<?php

use App\Livewire\Patients\ListPatients;
use App\Models\Patient;
use App\Models\User;
use Livewire\Livewire;

test('component renders successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListPatients::class)
        ->assertStatus(200);
});

test('displays list of patients', function () {
    $user = User::factory()->create();
    $patients = Patient::factory()->count(3)->create();

    $this->actingAs($user);

    $component = Livewire::test(ListPatients::class);

    foreach ($patients as $patient) {
        $component->assertSee($patient->name);
    }
});

test('search filters patients by name', function () {
    $user = User::factory()->create();
    $patient1 = Patient::factory()->create(['name' => 'John Doe']);
    $patient2 = Patient::factory()->create(['name' => 'Jane Smith']);

    $this->actingAs($user);

    Livewire::test(ListPatients::class)
        ->set('search', 'John')
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});

test('search filters patients by medical record number', function () {
    $user = User::factory()->create();
    $patient1 = Patient::factory()->create(['medical_record_number' => 'MR12345']);
    $patient2 = Patient::factory()->create(['medical_record_number' => 'MR99999']);

    $this->actingAs($user);

    Livewire::test(ListPatients::class)
        ->set('search', 'MR123')
        ->assertSee('MR12345')
        ->assertDontSee('MR99999');
});

test('search filters patients by phone', function () {
    $user = User::factory()->create();
    $patient1 = Patient::factory()->create([
        'name' => 'Patient One',
        'phone' => '081234567890',
    ]);
    $patient2 = Patient::factory()->create([
        'name' => 'Patient Two',
        'phone' => '089876543210',
    ]);

    $this->actingAs($user);

    $component = Livewire::test(ListPatients::class)
        ->set('search', '081234');

    $patients = $component->get('patients');

    expect($patients->pluck('id')->toArray())->toContain($patient1->id)
        ->and($patients->pluck('id')->toArray())->not->toContain($patient2->id);
});

test('patients are sorted by created_at descending by default', function () {
    $user = User::factory()->create();
    $oldPatient = Patient::factory()->create(['created_at' => now()->subDays(2)]);
    $newPatient = Patient::factory()->create(['created_at' => now()]);

    $this->actingAs($user);

    $component = Livewire::test(ListPatients::class);

    $patients = $component->get('patients');

    expect($patients->first()->id)->toBe($newPatient->id)
        ->and($patients->last()->id)->toBe($oldPatient->id);
});

test('can change sort direction', function () {
    $user = User::factory()->create();
    $oldPatient = Patient::factory()->create(['created_at' => now()->subDays(2)]);
    $newPatient = Patient::factory()->create(['created_at' => now()]);

    $this->actingAs($user);

    $component = Livewire::test(ListPatients::class)
        ->set('sortDirection', 'asc');

    $patients = $component->get('patients');

    expect($patients->first()->id)->toBe($oldPatient->id)
        ->and($patients->last()->id)->toBe($newPatient->id);
});

test('paginates patients with 10 per page', function () {
    $user = User::factory()->create();
    Patient::factory()->count(15)->create();

    $this->actingAs($user);

    $component = Livewire::test(ListPatients::class);

    $patients = $component->get('patients');

    expect($patients->count())->toBe(10)
        ->and($patients->total())->toBe(15);
});

test('search is case insensitive', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['name' => 'John Doe']);

    $this->actingAs($user);

    Livewire::test(ListPatients::class)
        ->set('search', 'john')
        ->assertSee('John Doe');

    Livewire::test(ListPatients::class)
        ->set('search', 'JOHN')
        ->assertSee('John Doe');
});

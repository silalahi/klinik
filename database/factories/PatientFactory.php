<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\PatientStatus;
use App\Enums\Religion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'medical_record_number' => 'MR' . fake()->unique()->numerify('######'),
            'id_number' => fake()->optional()->numerify('################'),
            'name' => fake()->name(),
            'place_of_birth' => fake()->city(),
            'date_of_birth' => fake()->dateTimeBetween('-80 years', '-1 year'),
            'gender' => fake()->randomElement(Gender::cases())->value,
            'marital_status' => fake()->randomElement(MaritalStatus::cases())->value,
            'religion' => fake()->randomElement(Religion::cases())->value,
            'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'occupation' => fake()->optional()->jobTitle(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
            'address' => fake()->address(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'emergency_contact_relationship' => fake()->randomElement(['Spouse', 'Parent', 'Sibling', 'Child', 'Friend']),
            'allergies' => fake()->optional()->sentence(),
            'medical_history' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(PatientStatus::cases())->value,
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}

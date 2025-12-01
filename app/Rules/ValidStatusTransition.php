<?php

namespace App\Rules;

use App\Enums\PatientStatus;
use App\Models\Patient;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidStatusTransition implements ValidationRule
{
    public function __construct(
        private Patient $patient
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Cannot change from Deceased
        if ($this->patient->status === PatientStatus::Deceased) {
            $fail(__('Cannot change status of deceased patients.'));

            return;
        }

        // Validate enum value using enum cases
        $valid = array_column(PatientStatus::cases(), 'value');

        if (! in_array($value, $valid, true)) {
            $fail(__('Invalid status selected.'));
        }
    }
}

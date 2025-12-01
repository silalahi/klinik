<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\PatientStatus;
use App\Enums\Religion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'medical_record_number',
        'id_number',
        'name',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'marital_status',
        'religion',
        'blood_type',
        'occupation',
        'phone',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'allergies',
        'medical_history',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gender' => Gender::class,
            'marital_status' => MaritalStatus::class,
            'religion' => Religion::class,
            'status' => PatientStatus::class,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function statusActivities()
    {
        return $this->activities()
            ->where('event', 'updated')
            ->whereNotNull('properties->attributes->status')
            ->orderBy('created_at', 'desc');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'logo',
        'settings',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Get the users for the clinic.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the doctors for the clinic.
     */
    public function doctors()
    {
        return $this->hasMany(User::class)->where('role', 'doctor');
    }

    /**
     * Get the patients for the clinic.
     */
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Get the appointments for the clinic.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the reminders for the clinic.
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}

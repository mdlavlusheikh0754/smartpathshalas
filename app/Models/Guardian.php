<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guardian extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'guardian';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'photo',
        'address',
        'occupation',
        'nid',
        'relation_to_student',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the default password based on student's date of birth
     * Format: ddmmyyyy (e.g., 15032010)
     */
    public function getDefaultPassword()
    {
        // Get the first student's date of birth as default password
        $student = $this->students()->first();
        if ($student && $student->date_of_birth) {
            return \Carbon\Carbon::parse($student->date_of_birth)->format('dmY');
        }
        return null;
    }

    /**
     * Set password to default (student's date of birth)
     */
    public function setDefaultPassword()
    {
        $defaultPassword = $this->getDefaultPassword();
        if ($defaultPassword) {
            $this->password = \Hash::make($defaultPassword);
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Check if guardian has a password set
     */
    public function hasPassword()
    {
        return !empty($this->password);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }
}

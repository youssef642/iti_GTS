<?php

// app/Models/Student.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'age', 'gender',
        'duration_track','track','address',
        'faculty', 'university', 'image', 'CV'
    ];

    protected $hidden = ['password'];
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

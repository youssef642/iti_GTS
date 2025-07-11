<?php

// app/Models/Student.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;
//con
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'age', 'gender',
        'faculty', 'university', 'track', 'image', 'CV'
    ];

    protected $hidden = ['password'];

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'student_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}

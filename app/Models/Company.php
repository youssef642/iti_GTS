<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phone', 'website', 'address', 'specialization', 'image',
        'description', 'facebook', 'linkedin', 'cover_image', 'type', 'team_size', 'founded', 'instagram', 'about'];

    protected $hidden = ['password', 'remember_token'];

public function jobPosts()
{
    return $this->hasMany(JobPost::class);
}
public function students(){
    return $this->hasMany(Student::class);
}
public function jobApplications()
{
    return $this->hasMany(JobApplication::class);
}
}

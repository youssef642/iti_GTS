<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'responsibilities',
        'min_salary',
        'max_salary',
        'location',
        'type',
        'is_remote',
        'experience',
        'published',
        'expires_at',
        'status',
        'company_id',
    ];

    // علاقة: وظيفة تتبع شركة
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // علاقة: وظيفة لها طلبات تقديم
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}

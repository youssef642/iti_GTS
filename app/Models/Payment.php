<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'company_id',
        'student_id',
        'job_application_id',
        'amount',
        'currency',
        'payment_intent_id',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }
}

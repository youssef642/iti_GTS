<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'job_post_id',
        'cover_letter',
    ];

   public function jobPost()
{
    return $this->belongsTo(JobPost::class);
}
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}

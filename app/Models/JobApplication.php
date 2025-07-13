<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class JobApplication extends Model
{
    use HasFactory;
    use HasApiTokens;
    
    protected $fillable = [
        'student_id',
        'job_post_id',
        'cover_letter',
        'cv',
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

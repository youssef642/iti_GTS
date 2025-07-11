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
    ];

    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}

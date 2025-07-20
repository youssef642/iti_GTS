<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'student_id' => $this->student_id,
            'student' => $this->student ? [
                'id' => $this->student->id,
                'name' => $this->student->name,
                'email' => $this->student->email,
            ] : null,
            'cv' => $this->cv,
            'cover_letter' => $this->message,
            'job_post' => [
                'id' => $this->jobPost->id,
                'title' => $this->jobPost->title,
                'status' => $this->jobPost->status,
            ],
            'student' => [
                'id' => $this->student->id,
                'name' => $this->student->name,
                'email' => $this->student->email,
                'image' => $this->student->image,
            ],
            'cv' => $this->cv ? asset('storage/' . $this->cv) : null,
            'cover_letter' => $this->cover_letter,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}

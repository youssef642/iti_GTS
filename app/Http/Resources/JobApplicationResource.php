<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'job_post' => [
                'id' => $this->jobPost->id,
                'title' => $this->jobPost->title,
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

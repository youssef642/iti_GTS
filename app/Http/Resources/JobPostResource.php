<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobPostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company' =>[
                'id' => $this->company->id,
                'name' => $this->company->name,
                'email' => $this->company->email,
            ],
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary,
            'location' => $this->location,
            'type' => $this->type,
            'is_remote' => $this->is_remote,
            'experience' => $this->experience,
            'published' => $this->published,
            'created_at' => $this->created_at?->toDateTimeString(),
            'status' => $this->status,
            'applications' => $this->whenCounted('jobApplications'),
        ];
    }
}

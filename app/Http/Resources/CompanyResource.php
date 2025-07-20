<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'about' => $this->about,
            'linkedin' => $this->linkedin,
            'website' => $this->website,
            'facebook' => $this->facebook,
            'created_at' => $this->created_at?->toDateTimeString(),
            'cover_image' => $this->cover_image,
            'type' => $this->type,
            'team_size' => $this->team_size,
            'founded' => $this->founded,
            'instagram' => $this->instagram,
            'specialization' => $this->specialization,
            'image' => $this->image,
        ];
    }
}

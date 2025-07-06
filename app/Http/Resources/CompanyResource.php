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
            'description' => $this->description,
            'linkedin' => $this->linkedin,
            'website' => $this->website,
            'facebook' => $this->facebook,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first name' => $this->first_name,
            'last name'=> $this->last_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'image' => $this->image,
        ];
    }
}

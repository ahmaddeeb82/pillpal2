<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'scientific name'=>$this->scientific_name,
            'commercial name' => $this->commercial_name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'expiration date' => $this->expiration_date,
            'company' => new CompanyResource($this->company),
            'category' => CategoryResource::collection($this->categories),
            'image' =>$this->image
        ];
    }
}

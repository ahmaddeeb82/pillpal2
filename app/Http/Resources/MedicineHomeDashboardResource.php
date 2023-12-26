<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineHomeDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'scientific name' => $this -> scientific_name,
            'commercial name' => $this -> commercial_name,
            'price' => $this -> price,
            'quantity' => $this -> quantity,
            'expiration date' => $this->expiration_date,
        ];
    }
}

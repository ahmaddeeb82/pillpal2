<?php

namespace App\Http\Resources;

use App\Models\MedicineOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineForOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'commercial name' => $this->commercial_name,
            'image' => $this->image,
            'quantity' => $this->whenPivotLoaded('medicine_order',function() {
                return $this->pivot->quantity;
            }),
            'quantity price' => $this->whenPivotLoaded('medicine_order',function() {
                return $this->pivot->quantity_price;
            }),
        ];
    }
}

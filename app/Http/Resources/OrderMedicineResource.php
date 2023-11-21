<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderMedicineResource extends JsonResource
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
            'user id'=>$this->user_id,
            'status' => $this->status,
            'payed' => $this->payed,
            'total price' => $this->total_price,
            'order date' => $this->order_date,
            'medicines' => MedicineResource::collection($this->medicines),
        ];
    }
}

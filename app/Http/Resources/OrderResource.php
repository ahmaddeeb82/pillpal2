<?php

namespace App\Http\Resources;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user name' => $this->user->first_name,
            'status' => $this->status,
            'payed' => $this->payed,
            'total price' => $this->total_price,
            'order date' => $this->order_date,
            'storehouse' => Admin::where('id', $this->admin_id)->first()->first_name,
        ];
    }
}

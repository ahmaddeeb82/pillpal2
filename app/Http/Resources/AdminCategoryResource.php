<?php

namespace App\Http\Resources;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCategoryResource extends JsonResource
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
            'name' =>[
                'en' =>$this->getTranslations('name')['en'],
                'ar' => $this -> getTranslations('name')['ar']
            ],
            'image' =>$this->image
        ];
    }
}

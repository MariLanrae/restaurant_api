<?php

namespace App\Http\Resources;

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
            'number' => $this->number,
            'closing_date' => $this->closing_date,
            'creation_date' => $this->creation_date,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'dishes' => $this->whenLoaded('dishes', $this->dishes->map(function ($dish){
                return [
                    'title' => $dish->title,
                    'quantity' => $dish->pivot->quantity,
                    'price' => $dish->price,
                    'sum' => $dish->pivot->quantity * $dish->price,
                    ];
            })),
        ];
    }

}

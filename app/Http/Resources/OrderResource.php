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
            'dishes' => $this->dishes->map('roles', function ($dishes) {
                return [
                    'title' => $dishes->title,
                    'quantity' => $dishes->pilot->quantity,
                ];
            }),
            'number' => $this->number,
            'closing_date' => $this->closing_date,
            'creation_date' => $this->creation_date,
            'status' => $this->status,
            'user' => $this->whenLoaded('user'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImportItemResource;

class ImportResource extends JsonResource
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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'items' => ImportItemResource::collection($this->whenLoaded('items')),
            'pagination' => $this->when($this->relationLoaded('items'), function () {
                return [
                    'current_page' => $this->items->currentPage(),
                    'last_page' => $this->items->lastPage(),
                    'per_page' => $this->items->perPage(),
                    'total' => $this->items->total(),
                ];
            })
        ];
    }
}

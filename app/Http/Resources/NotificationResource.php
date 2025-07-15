<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...$this->data,
            'id' => $this->id,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'created_for_humans' => $this->created_at->diffForHumans(),
        ];
    }
}

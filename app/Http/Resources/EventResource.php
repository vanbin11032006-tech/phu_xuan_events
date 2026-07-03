<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class EventResource extends JsonResource
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
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'location' => $this->location,
            'start_time' => $this->start_time ? $this->start_time->toIso8601String() : null,
            'end_time' => $this->end_time ? $this->end_time->toIso8601String() : null,
            'capacity' => $this->capacity,
            // whenCounted() returns the withCount value if loaded, otherwise falls back to collection count
            'registered_count' => $this->whenCounted(
                'registrations',
                $this->registrations_count ?? $this->registrations->where('status', '!=', 'cancelled')->count()
            ),
            'is_full' => $this->is_full,
            'status' => $this->status,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,
            'organizer' => $this->organizer ? [
                'id' => $this->organizer->id,
                'name' => $this->organizer->name,
            ] : null,
            'tags' => $this->tags->map(fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ]),
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}

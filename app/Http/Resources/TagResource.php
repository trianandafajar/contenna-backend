<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug'          => $this->slug,
            'name'          => $this->name,
            'blogs_count'   => $this->blogs_count,
            'blogs'         => $this->whenLoaded('blogs', fn($blog) => BlogListResource::collection($blog)),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'description'   => Str::limit(strip_tags($this->content), 150),
            'content'       => $this->content,
            'thumbnail' => $this->thumbnail
                ? url(Storage::url($this->thumbnail))
                : null,
            'featured_image' => $this->featured_image ? url(Storage::url($this->featured_image)) : null,
            'special_role'  => (int) $this->special_role,
            'status'        => $this->getStatusText(),
            'views'         => $this->views,
            'published_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'category' => $this->whenLoaded('category', fn() => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'author' => $this->whenLoaded('user', fn() => [
                'id'       => $this->user->id,
                'name'     => $this->user->name ?? 'Unknown Author',
                'username' => $this->user->username,
                'avatar'   => $this->user->avatar ? Storage::url($this->user->avatar) : null,
            ]),
            'tags' => $this->whenLoaded(
                'tags',
                fn() =>
                $this->tags->map(fn($tag) => [
                    'id'   => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ])
            ),
            'bookmark_count' => $this->whenCounted('bookmarkedBy'),
            'is_bookmarked' => $this->when(
                auth()->check(),
                fn() =>
                auth()->user()->bookmarks()->where('blogs.id', $this->id)->exists()
            ),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }

    /**
     * Get status text based on status code
     */
    private function getStatusText(): string
    {
        return match ($this->status) {
            '1' => 'Published',
            '2' => 'Draft',
            '0' => 'Archived',
            default => 'Unknown',
        };
    }
}

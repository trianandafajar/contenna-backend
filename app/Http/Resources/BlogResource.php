<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'special_role' => $this->special_role,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'published_at' => $this->created_at->format('Y-m-d H:i:s'),
            'category' => [
                'id' => $this->category->id ?? null,
                'name' => $this->category->name ?? null,
                'slug' => $this->category->slug ?? null,
            ],
            'author' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? 'Unknown Author',
                'username' => $this->user->username ?? null,
                'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            ],
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ];
            }),
            'bookmark_count' => $this->bookmarkedBy->count(),
            'is_bookmarked' => $this->when(
                auth()->check(),
                function () {
                    return auth()->user()->bookmarks()->where('blogs.id', $this->id)->exists();
                }
            ),
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

<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogListResource extends JsonResource
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
            'description'   => Str::limit(strip_tags($this->content), 150),
            'slug'          => $this->slug,
            'thumbnail' => $this->thumbnail
                ? url(Storage::url($this->thumbnail))
                : null,
            'views'         => $this->views,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id'   => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),
            'author' => $this->whenLoaded('user', function () {
                return [
                    'id'       => $this->user->id,
                    'name'     => $this->user->name ?? 'Unknown Author',
                    'username' => $this->user->username,
                    'avatar'   => $this->user->avatar ? Storage::url($this->user->avatar) : null,
                ];
            }),
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->map(fn ($tag) => [
                    'id'   => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ]);
            }),
            'bookmark_count' => $this->whenCounted('bookmarkedBy'),
            'is_bookmarked' => $this->when(auth()->check(), function () {
                return auth()->user()->bookmarks()->where('blogs.id', $this->id)->exists();
            }),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Tag::query();

        if ($request->filled('slug')) {
            $query->where('slug', $request->slug);
        }

        if ($request->filled('order')) {
            $orderField = ltrim($request->order, '-');
            $orderDir = str_starts_with($request->order, '-') ? 'desc' : 'asc';
            $query->orderBy($orderField, $orderDir);
        } else {
            $query->orderBy('name', 'asc');
        }

        if ($request->filled('limit') && is_numeric($request->limit)) {
            $query->limit($request->limit);
        }

        $tags = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'List Tags',
            'data' => TagResource::collection($tags),
        ]);
    }

    public function show(Tag $tag)
    {
        return response()->json([
            'success' => true,
            'message' => 'Tag detail',
            'data' => new TagResource($tag->load('blogs.tags')),
        ]);
    }
}

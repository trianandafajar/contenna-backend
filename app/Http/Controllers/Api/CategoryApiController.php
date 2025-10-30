<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->withCount('blogs')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Category',
            'data' => CategoryResource::collection($categories)
        ]);
    }

    public function show(Category $category)
    {
        if ($category->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->load([
            'blogs' => function ($query) {
                $query->where('status', 1)->latest();
            }
        ]);

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category)
        ]);
    }
}

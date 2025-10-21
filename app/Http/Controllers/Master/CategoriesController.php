<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Auth;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;
        $query = Category::orderBy('id');
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
                $q->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }
    
        $categories = $query->paginate($perPage);
        
        return view('pages.master.category.index', compact('categories'));
    }
    
    public function create()
    {
        return view("pages.master.category.create");
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'status'    => 'required',
        ]);

        $category            = new Category();
        $category->name      = $request->name;
        if($request->slug){
            $category->slug = $request->slug;
        }else{
            $category->slug = \Illuminate\Support\Str::slug($request->name, '-');
        }
        $category->status    = $request->status;
        $category->save();

        $message = [
            "status" => "success",
            "message" => "Data created successfully"
        ];

        if ($request->has('save_and_add_other')) {
            return redirect()->route('master.categories.create')->with("message", $message);
        } else {
            return redirect()->route('master.categories.index')->with("message", $message);
        }
    }
    
    public function edit($id)
    {
        $category = Category::where('id', $id)->first();
        return view("pages.master.category.edit", compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required',
            'status'    => 'required',
        ]);

        $category            = Category::where('id', $id)->first();
        $category->name      = $request->name;
        if ($category->slug != $request->slug) {
            $category->slug = \Illuminate\Support\Str::slug($request->name, '-');
        }
        $category->status    = $request->status;
        $category->update();

        $message = [
            "status" => "success",
            "message" => "Data updated successfully"
        ];

        return redirect()->route('master.categories.index')->with("message", $message);
    }
    
    public function show($id)
    {
        $category = Category::where('id', $id)->first();

        return view("pages.master.category.show", compact('category'));
    }

    public function destroy($id)
    {
        $category = Category::where('id', $id)->delete();

        $message = [
            "status" => "success",
            "message" => "Data deleted successfully"
        ];
        
        return redirect()->route('master.categories.index')->with("message", $message);
    }
}

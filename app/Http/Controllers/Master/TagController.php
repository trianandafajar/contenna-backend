<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;
        $query = Tag::orderBy('id');
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
    
        $tags = $query->paginate($perPage);
        
        return view('pages.master.tag.index', compact('tags'));
    }
    
    public function create()
    {
        return view("pages.master.tag.create");
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
        ]);

        $tag            = new Tag();
        $tag->name      = $request->name;
        if($request->slug){
            $tag->slug = $request->slug;
        }else{
            $tag->slug = \Illuminate\Support\Str::slug($request->name, '-');
        }
        $tag->save();

        $message = [
            "status" => "success",
            "message" => "Data created successfully"
        ];

        if ($request->has('save_and_add_other')) {
            return redirect()->route('master.tags.create')->with("message", $message);
        } else {
            return redirect()->route('master.tags.index')->with("message", $message);
        }
    }
    
    public function edit($id)
    {
        $tag = Tag::where('id', $id)->first();
        return view("pages.master.tag.edit", compact('tag'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required',
        ]);

        $tag            = Tag::where('id', $id)->first();
        $tag->name      = $request->name;
        if($request->slug){
            $tag->slug = $request->slug;
        }else{
            $tag->slug = \Illuminate\Support\Str::slug($request->name, '-');
        }
        $tag->update();

        $message = [
            "status" => "success",
            "message" => "Data updated successfully"
        ];

        if ($request->has('update_and_continue_editing')) {
            return redirect()->back()->with("message", $message);
        } else {
            return redirect()->route('master.tags.index')->with("message", $message);
        }
    }
    
    public function show($id)
    {
        $tag = Tag::where('id', $id)->first();

        return view("pages.master.tag.show", compact('tag'));
    }

    public function destroy($id)
    {
        $tag = Tag::where('id', $id)->delete();

        $message = [
            "status" => "success",
            "message" => "Data deleted successfully"
        ];
        
        return redirect()->route('master.tags.index')->with("message", $message);
    }
}

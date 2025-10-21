<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\Config;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:config-view', ['only' => ['index']]);
        $this->middleware('permission:config-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:config-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:config-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10; 
        $query = Config::orderBy('id');
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', '%'.$search.'%')
                ->orWhere('value', 'like', '%'.$search.'%');
            });
        }
        
        $configs = $query->orderByDesc('id')->paginate($perPage);
        
        return view('pages.resources.settings.configs.index', compact('configs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.resources.settings.configs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'nullable',
        ]);

        $config = new Config();
        $config->key = $request->key;
        $config->value = $request->value;
        $config->save();

        $message = [
            "status" => $config ? "success" : "failed",
            "message" => $config ? "Data created successfully" : "Data failed to create!"
        ];

        if ($request->has('save_and_add_other')) {

            return redirect()->route('resources.setting.config.create')->with("message", $message);
        } else {
            return redirect()->route('resources.setting.config.index')->with("message", $message);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $config = Config::findOrFail($id);

        return view('pages.resources.settings.configs.edit', compact('config'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'nullable',
        ]);

        $config = Config::findOrFail($id);
        $config->key = $request->key;
        $config->value = $request->value;
        $config->save();

        $message = [
            "status" => $config ? "success" : "failed",
            "message" => $config ? "Data updated successfully" : "Data failed to update!"
        ];

        if ($request->has('update_and_continue_editing')) {
            return Redirect::back()->with("message", $message);
        } else {
            return Redirect::route("resources.setting.config.index")->with("message", $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $config = Config::findOrFail($id);
        $config->delete();

        return redirect()->route('resources.setting.config.index')->with('success', 'Config deleted successfully.');
    }
}

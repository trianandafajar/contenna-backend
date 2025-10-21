<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\UpdateLog;
use App\Models\UpdateLogDescription;

class VersionLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:version-view', ['only' => ['index_version', 'index']]);
        $this->middleware('permission:version-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:version-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:version-delete', ['only' => ['destroy']]);
    }
    
    public function index_version()
    {
        $updateLogs = UpdateLog::with('descriptions')->orderBy('id','Desc')->paginate(5);
    
        return view('pages.version-logs.versions', compact('updateLogs'));
    }

    public function index()
    {
        $updateLogs = UpdateLog::paginate(10);

        return view('pages.version-logs.index', compact('updateLogs'));
    }

    public function create()
    {
        return view("pages.version-logs.create");
    }
    
    public function store(Request $request)
    {
        //menambah filed
        $request->validate([
            'title'         => 'required',
            'update_date'   => 'required',
        ]);

        $updateLog           = new UpdateLog();
        $updateLog->title     = $request->title;
        $updateLog->update_date  = $request->update_date;
        $updateLog->save();
        

        //Description
        $description        = $request->input('description');

        if (!empty($description)) {
            for ($i = 0; $i < count($description); $i++) {
                $updateLogDescription = new UpdateLogDescription();
                $updateLogDescription->log_id = $updateLog->id;
                $updateLogDescription->description = $description[$i];
                $updateLogDescription->save();
            }
        }

    

        $message = [
            "status" => "success",
            "message" => "Data created successfully"
        ];

        if ($request->has('save_and_add_other')) {
            return redirect()->route('log-version.create')->with("message", $message);
        } else {
            return redirect()->route('log-version.index')->with("message", $message);
        }
    }
    
    public function edit($id)
    {
        $updateLog = UpdateLog::where('id', $id)->first();
        $updateLogDescriptions = UpdateLogDescription::where('log_id', $id )->get();

        return view("pages.version-logs.edit", compact('updateLog','updateLogDescriptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'             => 'required',
            'update_date'       => 'required',
        ]);

        $updateLog                  = UpdateLog::where('id', $id)->first();
        $updateLog->title           = $request->title;
        $updateLog->update_date     = $request->update_date;
        
        $updateLog->update();

        //Update Log Description
        $description        = $request->input('description');

        if (!empty($description)) {
            // Delete existing descriptions
            UpdateLogDescription::where('log_id', $updateLog->id)->delete();

            for ($i = 0; $i < count($description); $i++) {
                $updateLogDescription = new UpdateLogDescription();
                $updateLogDescription->log_id = $updateLog->id;
                $updateLogDescription->description = $description[$i];
                $updateLogDescription->save();
            }
        }

        $message = [
            "status" => "success",
            "message" => "Data updated successfully"
        ];
        
        return redirect()->route('log-version.index')->with("message", $message);
    }
    
    public function show($id)
    {
        
    }

    public function destroy($id)
    {
        $updateLog = UpdateLog::where('id', $id)->delete();
        $updateLogDescription = UpdateLogDescription::where('log_id', $id)->delete();

        $message = [
            "status" => "success",
            "message" => "Data deleted successfully"
        ];
        
        return redirect()->route('log-version.index')->with("message", $message);
    }
}

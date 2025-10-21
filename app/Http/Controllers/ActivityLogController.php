<?php

namespace App\Http\Controllers;

use App\Models\UpdateLog;
use App\Models\UpdateLogDescription;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:activity-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:activity-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:activity-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:activity-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $activities = Activity::latest()->paginate(15);

        return view('pages.activity-logs.index', compact('activities'));
    }
}

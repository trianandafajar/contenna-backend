<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:feedback-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:feedback-create', ['only' => ['indexCreate', 'store']]);
        // $this->middleware('permission:feedback-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:feedback-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        // Ambil nilai pencarian dari input form
        $search = $request->input('search');

        // Query data feedback
        $feedData = Feedback::query();

        // Jika ada pencarian, filter data berdasarkan nama, email, atau message
        if ($search) {
            $feedData->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%'); // tambahkan pencarian pada kolom 'message'
            });
        }

        // Ambil data feedback yang telah difilter dan urutkan secara descending berdasarkan created_at
        $feedData = $feedData->latest()->get();

        // Kembalikan view dengan data feedback yang telah d filter
        return view('pages.feedback.index', compact('feedData'));
    }

    public function indexCreate()
    {
        return view('layouts/partials/_footer');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'user_role' => 'required',
            'feedback' => 'required',
            'file' => 'mimes:jpg,jpeg,png,mp4,avi|max:51200'
        ]);

        $name = Auth::user()->name;
        $email = Auth::user()->email;

        if($request->hasFile('file')) {
            $file       = $request->file('file');
            $filename   = $file->getClientOriginalName();
            $namaFile   = str_replace("#", "", $filename);
            $destination_path = public_path('/media/feedback/');
            $file->move($destination_path, $namaFile);
        } else {
            $namaFile   = null;
        }

        // Simpan data ke dalam database
        Feedback::create([
            'name' => $name,
            'email' => $email,
            'type' => $request->user_role,
            'file' => $namaFile,
            'message' => $request->feedback,
        ]);

        $message = [
            "status" => "success",
            "message" => "Feedback Successfully Sent!"
        ];

        // Redirect atau tampilkan pesan sukses
        return redirect()->back()->with('message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feedback = Feedback::findOrFail($id);
        return view('pages.feedback.show', ['feedback' => $feedback], compact('feedback')); // Teruskan data feedback ke view
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Resources;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users-view', ['only' => ['index']]);
        $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
        $this->middleware('permission:users-show', ['only' => ['show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;
        $query = User::whereNull('deleted_at');

        if ($search) {
            $this->applySearchFilter($query, $search);
        }

        $users = $query->orderByDesc('id')->paginate($perPage);

        return view('pages.resources.users.index', compact('users'));
    }

    public function create()
    {
        if (Auth::user()->hasRole('super-admin')) {
            $roles = Role::pluck('name', 'id')->toArray();
        } elseif (Auth::user()->hasRole('admin')) {
            $roles = Role::where('name', '!=', 'super-admin')->pluck('name', 'id')->toArray();
        } else {
            $roles = Role::where('name', '!=', 'super-admin')->where('name', '!=', 'admin')->pluck('name', 'id')->toArray();
        }

        return view('pages.resources.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'          => 'required',
            'email'         => 'required|string|max:255|email|unique:users',
            'password'      => 'nullable|min:8|same:password_confirmation',
            'avatar'        => 'nullable|file|image|max:1240',
            'role_id'       => 'required|array|min:1', // Pastikan role_id adalah array
            'role_id.*'     => 'exists:roles,id',
        ];

        $roles = $request->role_id;

        // $hasRole9 = in_array(9, $roles);
        $request->validate($rules);

        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;

        $namaFile = null;

        if ($request->file('avatar')) {
            $request->validate([
                'avatar' => 'required|file|image|max:1240',
            ]);

            $image = $request->file('avatar');
            $imagename = $image->getClientOriginalName();
            $a = explode(".", $imagename);
            $fileExt = strtolower(end($a));
            $namaFile = substr(md5(date("YmdHis")), 0, 10) . "." . $fileExt;
            $destination_path = public_path() . '/media/avatars/';
            $request->file('avatar')->move($destination_path, $namaFile);
        }

        $users->password = $request->password ? Hash::make($request->password) : Hash::make('docuverse123');
        $users->avatar = $namaFile;
        $users->email_verified_at = now();
        $users->status = 1;
        $users->save();

        $role = Role::findOrFail($request->role_id);
        $users->assignRole($role);
        
        $lastActivity = Activity::all()->last();
        
        $message = [
            "status" => $users ? "success" : "failed",
            "message" => $users ? "Data created successfully" : "Data failed to create!"
        ];

        if ($request->has('save_and_add_other')) {
            return redirect()->route('resources.users.create')->with("message", $message);
        } else {
            return redirect()->route('resources.users.index')->with("message", $message);
        }
    }

    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->hasRole('super-admin')) {
            $roles = Role::pluck('name', 'id')->toArray();
        } elseif (Auth::user()->hasRole('admin')) {
            $roles = Role::where('name', '!=', 'super-admin')->pluck('name', 'id')->toArray();
        } else {
            $roles = Role::where('name', '!=', 'super-admin')->where('name', '!=', 'admin')->pluck('name', 'id')->toArray();
        }
        return view('pages.resources.users.edit', compact('user', 'roles'));
    }

    /**
     * Display the specified resource.
     */
    function show(User $user)
    {
        $user->with('updatedByUser', 'createdByUser');

        return view('pages.resources.users.show', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi data input
        $rules= [
            'name'                  => 'required',
            'email'                 => 'required|email',
            'new_password'          => 'nullable|min:8|same:confirm_password',
            'confirm_password'      => 'nullable|min:8',
            'role_id'               => 'required',
        ];

        $request->validate($rules);

        // Update data pengguna
        $user->name             = $request->name;
        $user->email            = $request->email;

        $namaFile       = null;

        if ($request->file('avatar')) {
            if ($user->avatar != null) {
                $oldAvatar = public_path() . '/media/avatars/' . $user->avatar;

                File::delete($oldAvatar);
            }

            $request->validate([
                'avatar' => 'file|image|max:1240',
            ]);

            $image = $request->file('avatar');

            //name file
            $imagename = $image->getClientOriginalName();
            $a = explode(".", $imagename);
            $fileExt = strtolower(end($a));
            $namaFile = substr(md5(date("YmdHis")), 0, 10) . "." . $fileExt;

            //penyimpanan
            $destination_path = public_path() . '/media/avatars/';

            // simpan ke folder
            $request->file('avatar')->move($destination_path, $namaFile);

            // set avatar value
            $user->avatar = $namaFile;
        }

        // Periksa apakah user menghapus avatar
        if ($request->avatar_remove != null && $request->file("avatar") == null) {
            $oldAvatar = public_path() . '/media/avatars/' . $user->avatar;

            File::delete($oldAvatar);

            $user->avatar = null;
        }

        if ($request->filled('new_password')) {
            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        $role = Role::findOrFail($request->role_id);
        $user->syncRoles([$role]);

        $message = [
            "status" => $user ? "success" : "failed",
            "message" => $user ? "Data updated successfully" : "Data failed to update!"
        ];

        return Redirect::route("resources.users.index")->with("message", $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $user->delete();
        // if ($user->deleted_at != null) {
        //     $user->deleted_at = null;
        // } else {
        //     $user->deleted_at = Carbon::now();
        // }

        $message = [
            "status" => $user ? "success" : "failed",
            "message" => $user ? "User Remove successfully" : "Data failed to delete!"
        ];

        return back()->with('message', $message);
    }

    public function loginAsUser($id)
    {
        // Pastikan admin yang sedang masuk
        if (!Auth::check() || !Gate::allows('user-login-as')) {
            abort(403, 'Unauthorized');
        }

        $user = User::find($id);
        if (!$user) {
        abort(404, 'User not found');
    }

        Auth::loginUsingId($user->id);
        // Redirect ke halaman yang sesuai setelah login
        return redirect()->route('dashboard');
    }
    public function verifyEmail(Request $request, $userId)
    {
        $user = User::where('id', $userId)->first();
        $user->email_verified_at = Carbon::now();
        $user->save();

        $message = [
            "status" => $user ? "success" : "failed",
            "message" => $user ? "Email berhasil diverifikasi" : "Email gagal diverifikasi"
        ];

        return redirect()->back()->with('message', $message);
    }

    private function applySearchFilter($query, $search)
    {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    public function UserDeletedIndex(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;
        $query = User::onlyTrashed();

        if ($search) {
            $this->applySearchFilter($query, $search);
        }

        $users = $query->orderByDesc('id')->paginate($perPage);

        return view('pages.resources.user-deleted.index', compact('users'));
    }

    function UserDeletedShow($id)
    {
        // dd($id);
        $user = User::onlyTrashed()->find($id);
        
        
        return view('pages.resources.user-deleted.show', compact('user'));
    }

    public function UserDeletedRecovery($id)
    {

        $user = User::withTrashed()->find($id);

    if (!$user) {
        return redirect()->back()->with('message', ['status' => 'failed', 'message' => 'User not found!']);
    }

    $user->restore();

    $message = [
        "status" => $user ? "success" : "failed",
        "message" => $user ? "User Recovery successfully" : "Data failed to recovery!"
    ];

    return redirect()->route('resources.users.index')->with('message', $message);

    }

   

    public function UserDeletedPermanent($id)
    {
        $user = User::withTrashed()->find($id);

        if ($user->avatar) {
            $this->deleteFile('/media/avatars/', $user->avatar);
        }

        
        $user->forceDelete();

        $message = [
            "status" => $user ? "success" : "failed",
            "message" => $user ? "User Deleted successfully" : "Data failed to delete!"
        ];

        return back()->with('message', $message);
    }
}

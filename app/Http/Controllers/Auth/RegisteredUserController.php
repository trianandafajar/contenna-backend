<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Config;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Rules\StrongPassword;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Spatie\Activitylog\Models\Activity;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|string|max:255|email|unique:users',
            'password'      => 'required|min:8|same:password_confirmation',
            'registration_code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $config = Config::where('key', 'app.registration_code')->first();
                    if ($config && $config->value != $value) {
                        $fail('The registration code is incorrect.');
                    }
                },
            ],
        ]);

        $user                      = new User();
        $user->name                = $request->name;
        $user->email               = $request->email;
        $user->password            = Hash::make($request->password);
        $user->avatar              = null;
        $user->status              = 1;
        $user->save();

        $user->save();

        $user->assignRole('anggota');
        $lastActivity = Activity::all()->last();

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}

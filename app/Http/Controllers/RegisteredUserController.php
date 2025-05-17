<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Register Form
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Register User from Sign Up Form
     *
     * @param  UserStoreRequest $request
     * @return RedirectResponse
     */
    public function store(UserStoreRequest $request, UserService $userService): RedirectResponse
    {
        $attributes = $request->validated();

        $user = $userService->createUser($attributes);
        Auth::login($user);

        return redirect()->intended(route('citizen.dashboard'))->with('success', 'Registered Successfully! Great to have you here');
    }
}

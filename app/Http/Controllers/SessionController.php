<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginStoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionController extends Controller
{
    /**
     * View Login Form
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Create Session - login User
     *
     * @param  LoginStoreRequest $request
     * @return RedirectResponse
     */
    public function store(LoginStoreRequest $request): RedirectResponse
    {
        /** @var Request $request */
        if (!Auth::attempt($request->only([$request->loginField, 'password']))) {
            throw ValidationException::withMessages([
                'primary' => 'Invalid login credentials. Please try again.'
            ]);
        }

        $request->session()->regenerate();
        return $request->user()->redirectToDashboard();
    }

    /**
     * Logout The User
     *
     * @return RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect(route('home'));
    }
}

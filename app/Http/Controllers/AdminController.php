<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $admins = User::whereIn('role', [UserRole::ADMIN, UserRole::SUPER_ADMIN])->get();
        return view('admin.index', compact('admins'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.index');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('admin.index');
    }

    public function show(): RedirectResponse
    {
        return redirect()->route('admin.index');
    }

    public function edit(): RedirectResponse
    {
        return redirect()->route('admin.index');
    }

    public function update(): RedirectResponse
    {
        return redirect()->route('admin.index');
    }

    public function destroy(): RedirectResponse
    {
        return redirect()->route('admin.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\UserRole;
use Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show Admin List
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $role = $request->input('role') ? UserRole::tryfrom(titleCase($request->input('role'))) : '';
        // $role = ($role == "All") ? '' : $role;
        $query = User::query();

        switch ($role) {
            case UserRole::ADMIN:
                $query->where('role', UserRole::ADMIN);
                break;
            case UserRole::SUPER_ADMIN:
                $query->where('role', UserRole::SUPER_ADMIN);
                break;
            default:
                $query->whereIn('role', [UserRole::ADMIN, UserRole::SUPER_ADMIN]);
                break;
        }

        $admins = $query
            ->when($search, function (Builder $query, string $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.index', compact('admins'));
    }

    /**
     * Create Admin View
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.create');
    }

    /**
     * Create Admin Record
     *
     * @param  UserStoreRequest $request
     * @return RedirectResponse
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $role = UserRole::from(titleCase(request('role')));

        $profile_pic = request()->hasFile('profile-pic')
            ? request()->file('profile-pic')->store("profiles", "public")
            : null;

        $attributes = array_merge($attributes, [
            "role" => $role->value,
            "profile_pic" => $profile_pic
        ]);

        User::create($attributes);

        return redirect()->route('admin.management.index')->with('success', "Admin Created Successfully!!");
    }

    /**
     * Show Admin Details Page
     *
     * @return View
     */
    public function show(User $admin): View
    {
        return view('admin.show', compact('admin'));
    }

    public function edit(): RedirectResponse
    {
        return redirect()->route('admin.management.index');
    }

    public function update(): RedirectResponse
    {
        return redirect()->route('admin.management.index');
    }

    public function verify(): RedirectResponse
    {
        return redirect()->route('admin.management.index');
    }

    public function destroy(): RedirectResponse
    {
        return redirect()->route('admin.management.index');
    }
}

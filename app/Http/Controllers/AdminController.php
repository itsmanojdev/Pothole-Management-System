<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show Admin & Super Admin List
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

    /**
     * Admin Edit Page
     *
     * @param  User $user
     * @return View
     */
    public function edit(User $admin): View
    {
        return view('admin.edit', compact('admin'));
    }

    /**
     * Update Admin Details (Super Admin) / Update User Details from Profile
     *
     * @param  UserStoreRequest $request
     * @param  User $user
     * @return RedirectResponse
     */
    public function update(UserStoreRequest $request, User $user): RedirectResponse
    {
        $attributes = $request->validated();
        $attributes['role'] = titleCase($attributes['role']);
        $user->fill($attributes);
        $isProfile = request()->routeIs('user.profile');

        //if it's from profile, respective user can update their profile
        if ($isProfile && Auth::id() != $user->id) {
            abort(403, 'Oops!! Unauthorized Access');
        }

        if (request()->hasFile('profile-pic')) {
            Storage::disk('public')->delete($user->profile_pic ?? '');
            $user->profile_pic = request()->file('profile-pic')->store("profiles", "public");
        }

        // Save only if fields are dirty
        if ($user->isDirty()) {
            $user->save();

            return redirect()->route($isProfile ? 'profile' : 'admin.management.show', $user->id)->with('success', ($isProfile ? 'Profile' : 'Admin') . ' Details Updated Successfully!!');
        }
        return back()->with('info', 'No changes detected.');
    }

    /**
     * Change Password - Any Admin Password (Super Admin) / From Profile Page
     *
     * @param  ChangePasswordRequest $request
     * @param  User $user
     * @return RedirectResponse
     */
    public function changePassword(ChangePasswordRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $isProfile = request()->routeIs('user.profile');

        if (!$isProfile) {
            // Invalidate admin's sessions
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $user->password = $validated['new_password'];
        $user->save();
        return redirect()->route($isProfile ? 'profile' : 'admin.management.show', $user->id)->with('success', 'Password Updated Successfully!!');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $isProfile = request()->routeIs('user.profile');
        if ($request->delete === "DELETE") {
            if ($isProfile) {
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();
            }

            // Invalidate user's sessions -> Helps in invalidate admin's session if super admin deletes account
            DB::table('sessions')->where('user_id', $user->id)->delete();
            $user->delete();
            return redirect()->route($isProfile ? 'home' : 'admin.management.index')->with('success', ($isProfile ? 'Your Account' : 'Admin') . " Deleted Successfully!!");
        }
        return back()->with('error', "Type DELETE to delete the " . ($isProfile ? 'Account' : 'Admin'));
    }
}

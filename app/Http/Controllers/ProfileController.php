<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected UserService $userService) {}

    /**
     * User Profile Page
     *
     * @param  User $user
     * @return View
     */
    public function show(User $user): View
    {
        return view('profile', compact('user'));
    }

    /**
     * User Profile Edit Page
     *
     * @param  User $user
     * @return View
     */
    public function edit(User $user): View
    {
        $isEdit = true;
        return view('profile', compact('user', 'isEdit'));
    }

    /**
     * Profile Update
     *
     * @param  UserStoreRequest $request
     * @param  User $user
     * @return RedirectResponse
     */
    public function update(UserStoreRequest $request, User $user): RedirectResponse
    {
        $attributes = $request->validated();

        //Only respective user can update their profile
        if (Auth::id() != $user->id) {
            abort(403, 'Oops!! Unauthorized Access');
        }

        $savedUser = $this->userService->updateDetails($user, $attributes);
        if ($savedUser) {
            return redirect()->route('user.profile.show', $user->id)->with('success', 'User Details Updated Successfully!!');
        }
        return back()->with('info', 'No changes detected.');
    }

    /**
     * Change Password From Profile Page
     *
     * @param  ChangePasswordRequest $request
     * @param  User $user
     * @return RedirectResponse
     */
    public function changePassword(ChangePasswordRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $this->userService->changePassword($user, $validated['new_password']);
        return redirect()->route('user.profile.show', $user->id)->with('success', 'Password Updated Successfully!!');
    }

    /**
     * Delete User Account
     *
     * @param  User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        // Auth::logout();
        // request()->session()->invalidate();
        // request()->session()->regenerateToken();
        if ($this->userService->deleteUser($user)) {
            return redirect()->route('home')->with('success', "User Deleted Successfully!!");
        }
        return back()->with('error', "Type DELETE to delete the Account");
    }
}

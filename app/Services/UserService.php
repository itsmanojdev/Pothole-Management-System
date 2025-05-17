<?php

namespace App\Services;

use App\Models\User;
use App\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Create User
     *
     * @param  array $attributes
     * @return User
     */
    public function createUser(array $attributes): User
    {
        $role = request('role') ? UserRole::from(titleCase(request('role'))) : UserRole::CITIZEN;
        $profile_pic = request()->hasFile('profile-pic')
            ? request()->file('profile-pic')->store("profiles", "public")
            : null;

        $attributes = array_merge($attributes, [
            "role" => $role->value,
            "profile_pic" => $profile_pic
        ]);

        return User::create($attributes);
    }

    /**
     * Update User Details
     *
     * @param  User $user
     * @param  array $attributes
     * @return User
     */
    public function updateDetails(User $user, array $attributes): User|bool
    {
        $user->fill($attributes);

        if (request()->hasFile('profile-pic')) {
            Storage::disk('public')->delete($user->profile_pic ?? '');
            $user->profile_pic = request()->file('profile-pic')->store("profiles", "public");
        }

        // Save only if fields are dirty
        if ($user->isDirty()) {
            $user->save();
            return $user;
        }
        return false;
    }

    /**
     * Change Password
     *
     * @param  User $user
     * @param  string $newPassword
     * @return void
     */
    public function changePassword(User $user, string $newPassword): void
    {
        $user->password = $newPassword;
        $user->save();
    }

    /**
     * Delete User
     *
     * @param  User $user
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        if (request()->delete === "DELETE") {
            $user->delete();
            return true;
        }
        return false;
    }
}

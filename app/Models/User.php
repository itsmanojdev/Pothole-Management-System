<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_number',
        'aadhaar_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class
        ];
    }

    /**
     * Potholes created this user(Citizen)
     *
     * @return HasMany
     */
    public function createdPotholes(): HasMany
    {
        return $this->hasMany(Pothole::class, 'created_by');
    }


    /**
     * Potholes assigned to this user(Admin)
     *
     * @return HasMany
     */
    public function assignedPotholes(): HasMany
    {
        return $this->hasMany(Pothole::class, 'assigned_to');
    }

    /**
     * Redirect user to dashboard based on there role
     *
     * @param  string $returnType
     * @return RedirectResponse
     */
    public function redirectToDashboard(string $returnType = RedirectResponse::class): RedirectResponse|string
    {
        $route = match ($this->role) {
            UserRole::CITIZEN => 'citizen.dashboard',
            UserRole::SUPER_ADMIN, UserRole::ADMIN => 'admin.dashboard',
            default => 'home'
        };

        return $returnType == Route::class ? route($route) : redirect()->route($route)->with('success', "Login successful! Welcome back, " . Auth::user()->name);
    }

    /**
     * Returns true if user is super admin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->role == UserRole::SUPER_ADMIN;
    }

    /**
     * Returns true if user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role == UserRole::ADMIN;
    }

    /**
     * Returns true if user is citizen
     *
     * @return bool
     */
    public function isCitizen(): bool
    {
        return $this->role == UserRole::CITIZEN;
    }
}

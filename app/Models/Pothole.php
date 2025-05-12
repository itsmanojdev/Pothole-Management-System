<?php

namespace App\Models;

use App\PotholeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

/**
 * @mixin IdeHelperPothole
 */
class Pothole extends Model
{
    /** @use HasFactory<\Database\Factories\PotholeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'created_by',
        'assigned_to',
        'address',
        'latitude',
        'longitude',
        'image_path'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'status' => PotholeStatus::class
        ];
    }

    /**
     * User(Citizen) who created this pothole
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User(Admin) who got this pothole assigned to
     *
     * @return BelongsTo
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

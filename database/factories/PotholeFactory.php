<?php

namespace Database\Factories;

use App\Models\User;
use App\PotholeStatus;
use App\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pothole>
 */
class PotholeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->paragraph(5),
            'created_by' => User::where('role', UserRole::CITIZEN)->inRandomOrder()->first()?->id,
            'assigned_to' => null,
            'status' => PotholeStatus::OPEN->value,
            'latitude' => fake()->latitude(8.4, 37.6),
            'longitude' => fake()->longitude(68.7, 97.25),
            'address' => fake()->address(),
            'image_path' => "https://picsum.photos/seed/". fake()->numberBetween() ."/200/300",
        ];
    }
    
    /***********************
    * To assign pothole to admin/super admin *
    **********************/
    public function assigned(): static {

        $randomAdmin = User::whereIn('role', UserRole::adminAccess('string'))->inRandomOrder()->first();

        return $this->state(fn (array $attributes) => [
            'assigned_to' => $randomAdmin?->id,
            'status' => $randomAdmin ? PotholeStatus::ASSIGNED : PotholeStatus::OPEN
        ]);
    }
}

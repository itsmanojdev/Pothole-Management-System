<?php

use App\Models\User;
use App\PotholeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('potholes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained(table: 'users')
                ->nullOnDelete();
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained(table: 'users')
                ->nullOnDelete();
            $table->enum('status', PotholeStatus::casesInString())->default(PotholeStatus::OPEN->value);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('address');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potholes');
    }
};

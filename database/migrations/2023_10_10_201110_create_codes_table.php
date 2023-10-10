<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Door;
use App\Models\Code;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6);
            $table->timestamps();
        });

        Schema::create('doors_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Door::class);
            $table->foreignIdFor(Code::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doors_codes');
        Schema::dropIfExists('codes');
    }
};

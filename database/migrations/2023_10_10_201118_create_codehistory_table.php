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
        Schema::create('codehistory', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Door::class);
            $table->foreignIdFor(Code::class);
            $table->boolean('isActive')->default(true);
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codehistory');
    }
};

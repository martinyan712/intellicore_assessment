<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Door;
use App\Models\Group;
use App\Models\Rule;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fn_name');
            $table->timestamps();
        });

        Schema::create('doors_rules', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(Door::class);
			$table->foreignIdFor(Rule::class);
            $table->timestamps();
        });

        Schema::create('groups_rules', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(Group::class);
			$table->foreignIdFor(Rule::class);
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups_rules');
        Schema::dropIfExists('doors_rules');
        Schema::dropIfExists('rules');
    }
};

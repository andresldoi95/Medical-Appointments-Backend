<?php

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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->references('id')->on('teams');
            $table->string('identification', 20);
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_day');
            $table->string('address');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('city');
            $table->softDeletes();
            $table->timestamps();
            $table->unique([
                'team_id', 'identification'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};

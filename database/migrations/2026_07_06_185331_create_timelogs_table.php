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
        Schema::create('timelogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('work_date');

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('task_description');

            $table->unsignedTinyInteger('hours');
            $table->unsignedTinyInteger('minutes');
            $table->unsignedSmallInteger('total_minutes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelogs');
    }
};

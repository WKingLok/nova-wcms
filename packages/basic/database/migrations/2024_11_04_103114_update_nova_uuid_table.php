<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('action_events', function (Blueprint $table) {
            $table->uuid('actionable_id')->change();
            $table->uuid('target_id')->change();
            $table->uuid('model_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('action_events', function (Blueprint $table) {
            $table->unsignedBigInteger('actionable_id')->change();
            $table->unsignedBigInteger('target_id')->change();
            $table->unsignedBigInteger('model_id')->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->string('uuid')->nullable();
            $table->integer('ranking')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('widgets');
    }
};

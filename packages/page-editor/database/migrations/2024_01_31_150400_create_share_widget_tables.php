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
        Schema::create('share_widgets', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('uuid')->nullable();
            $table->string('name')->nullable();
            $table->string('component_key')->nullable();
            $table->boolean('enabled')->default(false);
            $table->boolean('system')->default(false);
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
        Schema::dropIfExists('share_widgets');
    }
};

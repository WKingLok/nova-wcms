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
        Schema::create('approvals', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('group_id')->nullable();
            $table->uuid('clone_from')->nullable();
            $table->uuid('approvable_id')->nullable();
            $table->string('approvable_type')->nullable();
            $table->string('status')->nullable();
            $table->string('action')->nullable();
            $table->string('version')->nullable();
            $table->json('history')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};

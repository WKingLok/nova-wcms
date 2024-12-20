<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temporary_uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('session_id');
            $table->timestamps();
        });
    }
};

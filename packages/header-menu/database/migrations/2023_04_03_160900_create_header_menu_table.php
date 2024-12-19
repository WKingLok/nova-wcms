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
        Schema::create('header_menus', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('parent_id')->nullable();
            $table->uuid('page_id')->nullable();
            $table->string('type')->nullable();
            $table->string('url')->nullable();
            $table->boolean('enabled')->default(false);
            $table->integer('ranking')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('header_menu_translations', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('header_menu_id')->constrained('header_menus')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('label')->nullable();
            $table->unique(['header_menu_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('header_menu_translations');
        Schema::dropIfExists('header_menus');
    }
};

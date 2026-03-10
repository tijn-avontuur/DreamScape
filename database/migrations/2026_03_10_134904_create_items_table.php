<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['weapon', 'armor', 'accessory', 'consumable', 'other']);
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->default('common');
            $table->unsignedTinyInteger('strength')->default(0);
            $table->unsignedTinyInteger('speed')->default(0);
            $table->unsignedTinyInteger('durability')->default(0);
            $table->string('magic_property')->nullable();
            $table->unsignedSmallInteger('required_level')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

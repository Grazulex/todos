<?php

declare(strict_types=1);

use App\Enums\TypeTodoEnum;
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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default(TypeTodoEnum::NORMAL->value);

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }
};

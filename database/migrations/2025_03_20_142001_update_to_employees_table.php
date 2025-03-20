<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {            
            $table->foreignId('department_id')->constrained()->onDelete('restrict');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
}; 
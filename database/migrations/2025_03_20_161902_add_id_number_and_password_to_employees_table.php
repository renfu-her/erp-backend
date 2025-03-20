<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // 先添加可為空的欄位
            $table->string('id_number')->nullable()->comment('身份證號碼');
            $table->string('password')->nullable()->comment('密碼');
        });

        // 如果有現有數據，需要先更新它們
        $employees = DB::table('employees')->get();
        foreach ($employees as $employee) {
            DB::table('employees')
                ->where('id', $employee->id)
                ->update([
                    'id_number' => 'TEMP' . uniqid(),
                    'password' => bcrypt('password'),
                    'birth_date' => now(),
                ]);
        }

        // 然後修改欄位為必填
        Schema::table('employees', function (Blueprint $table) {
            $table->string('id_number')->nullable(false)->unique()->change();
            $table->string('password')->nullable(false)->change();
            $table->date('birth_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['id_number', 'password']);
            
            // 恢復 birth_date 為可空
            $table->date('birth_date')->nullable()->change();
        });
    }
};

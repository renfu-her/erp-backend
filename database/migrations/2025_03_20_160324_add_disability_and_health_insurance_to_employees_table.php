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
        Schema::table('employees', function (Blueprint $table) {
            // 殘障相關欄位
            $table->string('disability_level')->default('none')->comment('殘障等級');
            $table->string('disability_card_number')->nullable()->comment('殘障手冊號碼');
            $table->date('disability_card_expiry')->nullable()->comment('殘障手冊有效期限');
            
            // 健保相關欄位
            $table->string('health_insurance_grade')->nullable()->comment('健保投保等級');
            $table->decimal('health_insurance_amount', 10, 2)->nullable()->comment('健保投保金額');
            
            // 索引
            $table->index('disability_level');
            $table->index('health_insurance_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // 移除索引
            $table->dropIndex(['disability_level']);
            $table->dropIndex(['health_insurance_grade']);
            
            // 移除欄位
            $table->dropColumn([
                'disability_level',
                'disability_card_number',
                'disability_card_expiry',
                'health_insurance_grade',
                'health_insurance_amount',
            ]);
        });
    }
};

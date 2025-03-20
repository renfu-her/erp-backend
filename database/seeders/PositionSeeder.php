<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'name' => '總經理',
                'code' => 'GM',
                'description' => '負責公司整體營運及策略規劃',
                'base_salary' => 150000,
                'benefits' => [
                    '年終獎金',
                    '績效獎金',
                    '股票選擇權',
                    '公司配車',
                    '醫療保險',
                ],
                'requirements' => [
                    '碩士以上學歷',
                    '10年以上管理經驗',
                    '優秀的領導能力',
                    '策略規劃能力',
                ],
            ],
            [
                'name' => '部門經理',
                'code' => 'DM',
                'description' => '負責部門營運及團隊管理',
                'base_salary' => 80000,
                'benefits' => [
                    '年終獎金',
                    '績效獎金',
                    '醫療保險',
                    '進修補助',
                ],
                'requirements' => [
                    '學士以上學歷',
                    '5年以上管理經驗',
                    '團隊領導能力',
                ],
            ],
            [
                'name' => '資深工程師',
                'code' => 'SE',
                'description' => '負責系統開發及技術指導',
                'base_salary' => 70000,
                'benefits' => [
                    '年終獎金',
                    '績效獎金',
                    '醫療保險',
                    '進修補助',
                ],
                'requirements' => [
                    '學士以上學歷',
                    '5年以上開發經驗',
                    '系統架構能力',
                ],
            ],
            [
                'name' => '工程師',
                'code' => 'ENG',
                'description' => '負責系統開發及維護',
                'base_salary' => 50000,
                'benefits' => [
                    '年終獎金',
                    '績效獎金',
                    '醫療保險',
                ],
                'requirements' => [
                    '學士以上學歷',
                    '2年以上開發經驗',
                    '程式設計能力',
                ],
            ],
            [
                'name' => '專案經理',
                'code' => 'PM',
                'description' => '負責專案規劃及執行',
                'base_salary' => 60000,
                'benefits' => [
                    '年終獎金',
                    '績效獎金',
                    '醫療保險',
                    '進修補助',
                ],
                'requirements' => [
                    '學士以上學歷',
                    '3年以上專案管理經驗',
                    'PMP認證優先',
                ],
            ],
            [
                'name' => '人資專員',
                'code' => 'HR',
                'description' => '負責人力資源相關事務',
                'base_salary' => 45000,
                'benefits' => [
                    '年終獎金',
                    '績效獎金',
                    '醫療保險',
                ],
                'requirements' => [
                    '學士以上學歷',
                    '2年以上人資經驗',
                    '溝通協調能力',
                ],
            ],
            [
                'name' => '會計專員',
                'code' => 'ACC',
                'description' => '負責財務會計相關事務',
                'base_salary' => 45000,
                'benefits' => [
                    '年終獎金',
                    '績效獎金',
                    '醫療保險',
                ],
                'requirements' => [
                    '學士以上學歷',
                    '2年以上會計經驗',
                    '會計師證照優先',
                ],
            ],
            [
                'name' => '一般員工',
                'code' => 'EMP',
                'description' => '負責一般行政及業務工作',
                'base_salary' => 35000,
                'benefits' => [
                    '年終獎金',
                    '績效獎金',
                    '醫療保險',
                ],
                'requirements' => [
                    '專科以上學歷',
                    '基本電腦操作能力',
                    '良好的溝通能力',
                ],
            ],
            [
                'name' => '工讀生',
                'code' => 'PT',
                'description' => '負責一般行政支援工作',
                'base_salary' => 180,
                'benefits' => [
                    '勞健保',
                    '加班費',
                ],
                'requirements' => [
                    '在學學生',
                    '基本電腦操作能力',
                    '可配合排班',
                ],
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => '總經理室',
                'code' => 'GM',
                'description' => '公司最高管理層，負責整體營運策略規劃與決策',
                'is_active' => true,
            ],
            [
                'name' => '人力資源部',
                'code' => 'HR',
                'description' => '負責人員招募、培訓、薪資、考勤等人事相關事務',
                'is_active' => true,
            ],
            [
                'name' => '財務部',
                'code' => 'FIN',
                'description' => '負責公司財務規劃、會計、出納等財務相關事務',
                'is_active' => true,
            ],
            [
                'name' => '資訊部',
                'code' => 'IT',
                'description' => '負責公司資訊系統開發、維護與網路管理',
                'is_active' => true,
            ],
            [
                'name' => '行銷部',
                'code' => 'MKT',
                'description' => '負責產品行銷、品牌推廣、市場調查等行銷相關事務',
                'is_active' => true,
            ],
            [
                'name' => '業務部',
                'code' => 'SALES',
                'description' => '負責客戶開發、訂單處理、客戶關係管理等業務相關事務',
                'is_active' => true,
            ],
            [
                'name' => '研發部',
                'code' => 'RD',
                'description' => '負責產品研發、技術創新、品質管理等研發相關事務',
                'is_active' => true,
            ],
            [
                'name' => '生產部',
                'code' => 'PROD',
                'description' => '負責產品製造、生產排程、品質控制等生產相關事務',
                'is_active' => true,
            ],
            [
                'name' => '客服部',
                'code' => 'CS',
                'description' => '負責客戶服務、問題處理、售後服務等客服相關事務',
                'is_active' => true,
            ],
            [
                'name' => '法務部',
                'code' => 'LEGAL',
                'description' => '負責法律諮詢、合約審查、智慧財產權等法務相關事務',
                'is_active' => true,
            ],
            [
                'name' => '行政部',
                'code' => 'ADMIN',
                'description' => '負責辦公室管理、總務、採購等行政相關事務',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Công nghệ thông tin',
                'description' => 'Các sự kiện về lập trình, trí tuệ nhân tạo, an toàn thông tin và khoa học máy tính.',
            ],
            [
                'name' => 'Kinh tế & Quản trị',
                'description' => 'Hội thảo chuyên đề khởi nghiệp, quản trị kinh doanh, marketing và tài chính.',
            ],
            [
                'name' => 'Ngôn ngữ & Văn hóa',
                'description' => 'Giao lưu văn hóa, nâng cao trình độ ngoại ngữ (Tiếng Anh, Tiếng Trung, Tiếng Nhật).',
            ],
            [
                'name' => 'Nghệ thuật & Thiết kế',
                'description' => 'Triển lãm tranh ảnh, workshop thiết kế đồ họa, thời trang và kiến trúc.',
            ],
            [
                'name' => 'Kỹ năng mềm',
                'description' => 'Rèn luyện kỹ năng giao tiếp, thuyết trình, làm việc nhóm và định hướng nghề nghiệp.',
            ],
            [
                'name' => 'Thể thao & Sức khỏe',
                'description' => 'Giải đấu bóng đá, cầu lông, hiến máu nhân đạo và ngày hội thể thao sinh viên.',
            ],
            [
                'name' => 'Hội thảo khoa học',
                'description' => 'Báo cáo nghiên cứu khoa học của sinh viên và giảng viên PXU.',
            ],
            [
                'name' => 'Hoạt động Đoàn Hội',
                'description' => 'Các chiến dịch tình nguyện, đại hội Đoàn trường và sinh hoạt văn nghệ tập thể.',
            ]
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description']
            ]);
        }
    }
}

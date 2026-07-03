<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vietnameseTitles = [
            'Ngày hội việc làm IT 2026',
            'Hội thảo AI & Machine Learning',
            'Cuộc thi lập trình Hackathon PXU',
            'Workshop Laravel từ A-Z',
            'Ngày hội sinh viên nghiên cứu khoa học',
            'Hội thảo Khởi nghiệp sáng tạo và Chuyển đổi số',
            'Workshop Kỹ năng mềm trong kỷ nguyên số',
            'Hội nghị Khoa học Công nghệ thường niên',
            'Ngày hội tuyển dụng PXU Career Day',
            'Giải bóng đá sinh viên tranh cúp Phú Xuân',
            'Tọa đàm Gặp gỡ doanh nghiệp và cựu sinh viên tiêu biểu',
            'Hội thảo IoT & Smart City',
            'Đêm nhạc hội chào đón tân sinh viên',
            'Hội thảo Du học và Học bổng quốc tế',
            'Tập huấn Kỹ năng nghiên cứu khoa học cho giảng viên trẻ',
            'Talkshow Định hướng nghề nghiệp ngành Ngôn ngữ Anh',
            'Lễ hội ẩm thực và văn hóa PXU Food Fest',
            'Workshop Thiết kế đồ họa bằng Canva và Figma',
            'Hội thảo Bảo mật thông tin trong thời đại AI',
            'Ngày hội hiến máu nhân đạo PXU Pink Sunday'
        ];

        $locations = [
            'Hội trường A - Tầng 2 Nhà Hiệu bộ',
            'Phòng máy B2 - Khu thực hành CNTT',
            'Sân Campus chính - Trường ĐH Phú Xuân',
            'Phòng hội thảo C - Thư viện trung tâm',
            'Hội trường B - Khu giảng đường D',
            'Không gian tự học PXU Hub',
            'Nhà thi đấu đa năng PXU Sport'
        ];

        $startTime = fake()->dateTimeBetween('now', '+3 months');
        // Calculate end_time based on start_time
        $endTime = clone $startTime;
        $endTime->modify('+' . rand(2, 8) . ' hours');

        return [
            'title' => fake()->randomElement($vietnameseTitles) . ' ' . fake()->unique()->numberBetween(1, 100),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->randomElement($locations),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'capacity' => fake()->randomElement([30, 50, 100, 200, 500]),
            'status' => fake()->randomElement(['published', 'published', 'published', 'draft']), // ~75% published, 25% draft
            'user_id' => User::where('role', 'organizer')->inRandomOrder()->first()?->id ?? User::factory()->organizer(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'image' => null,
        ];
    }
}

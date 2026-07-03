<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Laravel', 'PHP', 'React', 'AI', 'IoT', 'Machine Learning', 
            'Kỹ năng mềm', 'Phỏng vấn', 'CV', 'Khởi nghiệp', 
            'Hackathon', 'Tình nguyện', 'Bóng đá', 'Figma', 
            'Bảo mật', 'Ngoại ngữ', 'Học bổng', 'Sách'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag)
            ]);
        }
    }
}

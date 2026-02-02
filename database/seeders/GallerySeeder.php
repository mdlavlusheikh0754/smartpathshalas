<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            [
                'title' => 'স্কুল পরিচিতি ভিডিও',
                'description' => 'আমাদের স্কুলের পরিচিতিমূলক ভিডিও',
                'type' => 'video',
                'category' => 'পরিচিতি',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'শিক্ষা কার্যক্রম',
                'description' => 'আমাদের শিক্ষা কার্যক্রমের ভিডিও',
                'type' => 'video',
                'category' => 'শিক্ষা',
                'video_url' => 'https://www.youtube.com/embed/ScMzIvxBSi4',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'সাংস্কৃতিক অনুষ্ঠান',
                'description' => 'বার্ষিক সাংস্কৃতিক অনুষ্ঠানের ভিডিও',
                'type' => 'video',
                'category' => 'সাংস্কৃতিক',
                'video_url' => 'https://www.youtube.com/embed/9bZkp7q19f0',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }
    }
}
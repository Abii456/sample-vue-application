<?php

namespace Database\Seeders\Defaults;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run()
    {
        $samplePosts = require database_path('seeders/Defaults/Data/posts.php');

        foreach ($samplePosts as $post) {
            Post::create([
                'title' => $post['title'],
                'type' => $post['type'],
                'post' => $post['post'],
                'author_id' => $post['author_id'],
            ]);
        }
    }
}

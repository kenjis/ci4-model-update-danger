<?php

namespace App\Database\Seeds;

use App\Models\NewsModel;
use CodeIgniter\Database\Seeder;
use Faker\Factory as FakerFactory;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $model = model(NewsModel::class);
        $faker = FakerFactory::create();

        for ($i = 0; $i < 10; $i++) {
            $title = $faker->sentence();
            $data  = [
                'title' => $title,
                'slug'  => url_title($title, '-', true),
                'body'  => $faker->realText(),
            ];

            $model->save($data);
        }
    }
}

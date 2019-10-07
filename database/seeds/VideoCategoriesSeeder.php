<?php

use Illuminate\Database\Seeder;
use App\Models\VideoCategory;

class VideoCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Environnementtttttt',
                'description' => '',
                'slug' => 'environment'
            ],
            [
                'name' => 'Monde',
                'description' => '',
                'slug' => 'world'
            ],
            [
                'name' => 'Société',
                'description' => '',
                'slug' => 'society'
            ],
            [
                'name' => 'Futur',
                'description' => '',
                'slug' => 'future'
            ],
            [
                'name' => 'Identités',
                'description' => '',
                'slug' => 'identities'
            ],
            [
                'name' => 'Voyage + Food',
                'description' => '',
                'slug' => 'travel-food'
            ],
            [
                'name' => 'Culture',
                'description' => '',
                'slug' => 'culture'
            ],
            [
                'name' => 'Insolite',
                'description' => '',
                'slug' => 'unusual'
            ],
            [
                'name' => 'VoxPop',
                'description' => '',
                'slug' => 'voxpop'
            ],
            [
                'name' => 'Tribune',
                'description' => '',
                'slug' => 'tribune'
            ],

        ];
        foreach ($categories as $key => $category) {
            VideoCategory::create($category);
        }
    }
}

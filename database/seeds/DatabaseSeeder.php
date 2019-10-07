<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call(UsersSeeder::class);
      $this->call(VideoCategoriesSeeder::class);
      $this->call(RolesPermissionsSeeder::class);
    }
}

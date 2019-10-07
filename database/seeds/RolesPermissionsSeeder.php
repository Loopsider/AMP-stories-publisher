<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author = new Role();
        $author->name         = 'author';
        $author->display_name = 'Author';
        $author->description  = 'User can create data in the system';
        $author->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Administrator';
        $admin->description  = 'User has access to all system functionality';
        $admin->save();

        $user = User::where('email', '=', env('ADMIN_EMAIL'))->first();
        $user->attachRole($admin);

        $createStory = new Permission();
        $createStory->name         = 'create-story';
        $createStory->display_name = 'Create Stories';
        $createStory->description  = 'create new stories';
        $createStory->save();

        $createVideo = new Permission();
        $createVideo->name         = 'create-video';
        $createVideo->display_name = 'Create Videos';
        $createVideo->description  = 'create new videos';
        $createVideo->save();

        $editUser = new Permission();
        $editUser->name         = 'edit-user';
        $editUser->display_name = 'Edit Users';
        $editUser->description  = 'edit existing users';
        $editUser->save();

        $admin->attachPermission(array($editUser, $createVideo, $createStory));
        $author->attachPermissions(array($createVideo, $createStory));
    }
}

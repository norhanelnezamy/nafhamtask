<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(['username' => 'user1','email' => str_random(10).'@gmail.com','password' => bcrypt('1234')]);
        DB::table('users')->insert(['username' => 'user2','email' => str_random(10).'@gmail.com','password' => bcrypt('1234')]);
        DB::table('users')->insert(['username' => 'user3','email' => str_random(10).'@gmail.com','password' => bcrypt('1234')]);
    }
}
